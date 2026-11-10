import { MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, ToggleControl, SelectControl, Button } from "@wordpress/components";

// Text is stored as HTML (e.g. "&amp;"); show and type it as plain text in the sidebar.
export const dec = (v) => String(v || "").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&amp;/g, "&");
export const enc = (v) => String(v || "").replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

// Preview-only HTML (the canvas mirrors the front end; content is edited in the sidebar).
export const Html = ({ tag: Tag = "span", value, ...rest }) => <Tag {...rest} dangerouslySetInnerHTML={{ __html: value || "" }} />;

function ImageField({ field, item, update }) {
	const idKey = field.idKey || "imageId";
	const urlKey = field.urlKey || "imageUrl";
	const url = item[urlKey];
	return (
		<div style={{ marginBottom: 16 }}>
			<div style={{ fontWeight: 600, marginBottom: 6 }}>{field.label || "Ảnh"}</div>
			{url && <img src={url} alt="" style={{ display: "block", width: "100%", height: 110, objectFit: "cover", borderRadius: 6, marginBottom: 8 }} />}
			<MediaUploadCheck>
				<MediaUpload
					onSelect={(m) => update({ [idKey]: m.id, [urlKey]: m.url })}
					allowedTypes={["image"]}
					value={item[idKey]}
					render={({ open }) => (
						<Button variant="secondary" onClick={open}>
							{url ? "Đổi ảnh" : "Chọn ảnh"}
						</Button>
					)}
				/>
			</MediaUploadCheck>
			{url && (
				<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => update({ [idKey]: 0, [urlKey]: "" })}>
					Bỏ ảnh
				</Button>
			)}
		</div>
	);
}

// "socials.facebook" style keys read and write one level of nesting.
const getVal = (item, key) => key.split(".").reduce((o, k) => (o == null ? o : o[k]), item);
const setVal = (item, key, value) => {
	const [head, tail] = key.split(".");
	return tail === undefined ? { [head]: value } : { [head]: { ...(item[head] || {}), [tail]: value } };
};

function Field({ field, item: rawItem, update: rawUpdate }) {
	const { key, label, help, type = "text" } = field;
	// Present the item as if the dotted key were a flat key.
	const item = new Proxy(rawItem, { get: (t, prop) => (typeof prop === "string" && prop.includes(".") ? getVal(t, prop) : t[prop]) });
	const update = (patch) => {
		const merged = {};
		Object.entries(patch).forEach(([k, v]) => Object.assign(merged, k.includes(".") ? setVal(rawItem, k, v) : { [k]: v }));
		rawUpdate(merged);
	};
	switch (type) {
		case "textarea":
			return <TextareaControl label={label} help={help} rows={field.rows || 4} value={dec(item[key])} onChange={(v) => update({ [key]: enc(v) })} />;
		case "multiline": {
			// Line breaks are stored as <br>; edit them as real new lines.
			const toText = (v) => dec(String(v || "").replace(/<br\s*\/?>/gi, "\n"));
			return (
				<TextareaControl
					label={label}
					help={help || "Xuống dòng bằng phím Enter"}
					rows={field.rows || 2}
					value={toText(item[key])}
					onChange={(v) => update({ [key]: enc(v).split("\n").join("<br>") })}
				/>
			);
		}
		case "lines":
			return (
				<TextareaControl
					label={label}
					help={help || "Mỗi dòng một ý"}
					rows={field.rows || 5}
					value={(item[key] || []).map(dec).join("\n")}
					onChange={(v) => update({ [key]: v.split("\n").map(enc) })}
				/>
			);
		case "url":
			return <TextControl label={label} help={help} value={item[key] || ""} onChange={(v) => update({ [key]: v })} />;
		case "image":
			return <ImageField field={field} item={item} update={update} />;
		case "toggle":
			return <ToggleControl label={label} help={help} checked={!!item[key]} onChange={(v) => update({ [key]: v })} />;
		case "select":
			return <SelectControl label={label} help={help} value={item[key] || ""} options={field.options} onChange={(v) => update({ [key]: v })} />;
		default:
			return <TextControl label={label} help={help} value={dec(item[key])} onChange={(v) => update({ [key]: enc(v) })} />;
	}
}

/**
 * Sidebar manager for a repeating list: one collapsible panel per item with its fields,
 * move up / down / delete, and an "add" button.
 *
 * fields: [{ key, label, type: text|textarea|lines|url|image|toggle|select, help, rows, options, idKey, urlKey }]
 */
export default function ItemsPanel({ title, hint, items = [], setItems, fields, makeItem, itemLabel, addLabel = "+ Thêm", initialOpen = true, active, setActive, min = 0, max = 99 }) {
	const update = (i, patch) => setItems(items.map((it, k) => (k === i ? { ...it, ...patch } : it)));
	const remove = (i) => {
		setItems(items.filter((_, k) => k !== i));
		if (setActive) setActive(0);
	};
	const move = (i, dir) => {
		const j = i + dir;
		if (j < 0 || j >= items.length) return;
		const next = [...items];
		[next[i], next[j]] = [next[j], next[i]];
		setItems(next);
		if (setActive) setActive(j);
	};

	return (
		<PanelBody title={`${title} (${items.length})`} initialOpen={initialOpen}>
			{hint && <p style={{ marginTop: 0, color: "#757575" }}>{hint}</p>}
			{items.map((item, i) => (
				<PanelBody key={i} title={`${i + 1}. ${itemLabel ? itemLabel(item, i) : ""}`} initialOpen={false} onToggle={() => setActive && setActive(i)}>
					{fields.map((f, k) => (
						<Field key={k} field={f} item={item} update={(patch) => update(i, patch)} />
					))}
					<div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
						<Button variant="secondary" disabled={i === 0} onClick={() => move(i, -1)}>
							↑ Lên
						</Button>
						<Button variant="secondary" disabled={i === items.length - 1} onClick={() => move(i, 1)}>
							↓ Xuống
						</Button>
						{items.length > min && (
							<Button isDestructive variant="link" onClick={() => remove(i)}>
								Xoá mục này
							</Button>
						)}
					</div>
				</PanelBody>
			))}
			{items.length < max && (
				<Button
					variant="primary"
					style={{ marginTop: 12 }}
					onClick={() => {
						setItems([...items, makeItem(items.length)]);
						if (setActive) setActive(items.length);
					}}
				>
					{addLabel}
				</Button>
			)}
		</PanelBody>
	);
}

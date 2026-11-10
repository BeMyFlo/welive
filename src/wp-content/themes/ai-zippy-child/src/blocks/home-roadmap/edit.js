import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button, SelectControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

// Text is stored as HTML (e.g. "&amp;"); show and type it as plain text in the sidebar.
const dec = (v) => String(v || "").replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
const enc = (v) => String(v || "").replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

const emptyTab = () => ({ days: "NGÀY ", label: "Tab mới", panelTitle: "", bullets: [], imageId: 0, imageUrl: "", alt: "" });

export default function Edit({ attributes, setAttributes }) {
	const { kicker, title, intro, ctaText, ctaUrl, cardCta, cards = [], layout, tabs = [], image, note } = attributes;
	const isTabs = layout === "tabs";
	const [active, setActive] = useState(0);
	const blockProps = useBlockProps({ className: isTabs ? "hrm hrm--tabs" : "hrm" });
	const photo = image || {};

	// ---- cards layout helpers ----
	const setCard = (i, patch) => setAttributes({ cards: cards.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	// ---- tabs layout helpers ----
	const setTab = (i, patch) => setAttributes({ tabs: tabs.map((t, k) => (k === i ? { ...t, ...patch } : t)) });
	const removeTab = (i) => {
		setAttributes({ tabs: tabs.filter((_, k) => k !== i) });
		setActive(0);
	};
	const moveTab = (i, dir) => {
		const j = i + dir;
		if (j < 0 || j >= tabs.length) return;
		const next = [...tabs];
		[next[i], next[j]] = [next[j], next[i]];
		setAttributes({ tabs: next });
		setActive(j);
	};
	const current = Math.min(active, Math.max(tabs.length - 1, 0));
	const cur = tabs[current];
	const tabImage = (t) => t.imageUrl || photo.imageUrl || "";

	return (
		<>
			<InspectorControls>
				<PanelBody title="Layout" initialOpen={!isTabs}>
					<SelectControl
						label="Layout"
						value={isTabs ? "tabs" : "cards"}
						options={[
							{ label: "Tab ngày + khung chi tiết (mới)", value: "tabs" },
							{ label: "4 card (cũ)", value: "cards" },
						]}
						onChange={(v) => setAttributes({ layout: v })}
					/>
				</PanelBody>

				{isTabs && (
					<PanelBody title={`Các tab (${tabs.length})`} initialOpen={true}>
						<p style={{ marginTop: 0, color: "#757575" }}>Bấm tab trên canvas để xem trước, rồi sửa nội dung từng tab ở đây.</p>
						{tabs.map((t, i) => (
							<PanelBody key={i} title={`${i + 1}. ${dec(t.days)} ${dec(t.label)}`} initialOpen={i === current} onToggle={() => setActive(i)}>
								<TextControl label="Ngày (nhãn nhỏ)" value={dec(t.days)} onChange={(v) => setTab(i, { days: enc(v) })} />
								<TextControl label="Tên tab" value={dec(t.label)} onChange={(v) => setTab(i, { label: enc(v) })} />
								<TextControl label="Tiêu đề trong khung" value={dec(t.panelTitle)} onChange={(v) => setTab(i, { panelTitle: enc(v) })} />
								<TextareaControl
									label="Các ý (mỗi dòng một ý)"
									value={(t.bullets || []).map(dec).join("\n")}
									rows={5}
									onChange={(v) => setTab(i, { bullets: v.split("\n").map(enc) })}
								/>
								<div style={{ marginBottom: 12 }}>
									<div style={{ fontWeight: 600, marginBottom: 6 }}>Ảnh của tab này</div>
									{tabImage(t) && <img src={tabImage(t)} alt="" style={{ display: "block", width: "100%", height: 110, objectFit: "cover", borderRadius: 6, marginBottom: 8 }} />}
									<MediaUploadCheck>
										<MediaUpload
											onSelect={(m) => setTab(i, { imageId: m.id, imageUrl: m.url })}
											allowedTypes={["image"]}
											value={t.imageId}
											render={({ open }) => (
												<Button variant="secondary" onClick={open}>
													{t.imageUrl ? "Đổi ảnh" : "Chọn ảnh"}
												</Button>
											)}
										/>
									</MediaUploadCheck>
									{t.imageUrl && (
										<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setTab(i, { imageId: 0, imageUrl: "" })}>
											Bỏ ảnh
										</Button>
									)}
								</div>
								<TextControl label="Alt ảnh" value={t.alt || ""} onChange={(v) => setTab(i, { alt: v })} />
								<div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
									<Button variant="secondary" disabled={i === 0} onClick={() => moveTab(i, -1)}>↑ Lên</Button>
									<Button variant="secondary" disabled={i === tabs.length - 1} onClick={() => moveTab(i, 1)}>↓ Xuống</Button>
									<Button isDestructive variant="link" onClick={() => removeTab(i)}>Xoá tab</Button>
								</div>
							</PanelBody>
						))}
						<Button
							variant="primary"
							style={{ marginTop: 12 }}
							onClick={() => {
								setAttributes({ tabs: [...tabs, emptyTab()] });
								setActive(tabs.length);
							}}
						>
							+ Thêm tab
						</Button>
					</PanelBody>
				)}

				<PanelBody title="Nút chính" initialOpen={false}>
					<TextControl label="Anchor (id để link tới, vd #lo-trinh)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link nút chính" value={ctaUrl} onChange={(v) => setAttributes({ ctaUrl: v })} />
					{!isTabs && <TextControl label="Chữ cuối mỗi card" value={cardCta} onChange={(v) => setAttributes({ cardCta: v })} />}
				</PanelBody>

				{!isTabs && (
					<PanelBody title="Card" initialOpen={false}>
						{cards.map((c, i) => (
							<TextControl key={i} label={`Link riêng card ${i + 1} (để trống = link mặc định)`} value={c.url || ""} onChange={(v) => setCard(i, { url: v })} />
						))}
						<Button variant="secondary" onClick={() => setAttributes({ cards: [...cards, { index: String(cards.length + 1).padStart(2, "0"), days: "", title: "", detail: "", url: "" }] })}>
							+ Thêm card
						</Button>
						{cards.length > 0 && (
							<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ cards: cards.slice(0, -1) })}>
								Xoá card cuối
							</Button>
						)}
					</PanelBody>
				)}
			</InspectorControls>

			<section {...blockProps}>
				<div className="hrm__wrap">
					<div className="hrm__top">
						<div>
							<RichText tagName="p" className="hrm__kicker" value={kicker} onChange={(v) => setAttributes({ kicker: v })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<RichText tagName="h2" className="hrm__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" />
						</div>
						<span className="hrm__pill-mock">
							<RichText tagName="span" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} placeholder="Nút" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<RichText tagName="p" className="hrm__intro" value={intro} onChange={(v) => setAttributes({ intro: v })} placeholder="Mô tả ngắn" />

					{isTabs ? (
						<>
							{/* Preview only: tab content is edited in the sidebar. */}
							<div className="hrm__tabs">
								{tabs.map((t, i) => (
									<div key={i} className="hrm__tab" role="button" tabIndex={0} aria-selected={i === current ? "true" : "false"} onClick={() => setActive(i)} onKeyDown={(e) => (e.key === "Enter" || e.key === " ") && setActive(i)}>
										<span className="hrm__days" dangerouslySetInnerHTML={{ __html: t.days || "" }} />
										<strong dangerouslySetInnerHTML={{ __html: t.label || "" }} />
									</div>
								))}
							</div>
							<div className="hrm__detail-box">
								{cur ? (
									<div className="hrm__panel">
										<div className="hrm__panel-text">
											<p className="hrm__days" dangerouslySetInnerHTML={{ __html: cur.days || "" }} />
											<h3 dangerouslySetInnerHTML={{ __html: cur.panelTitle || "" }} />
											<ul>
												{(cur.bullets || []).filter((b) => b.trim() !== "").map((b, j) => (
													<li key={j} dangerouslySetInnerHTML={{ __html: b }} />
												))}
											</ul>
										</div>
										{tabImage(cur) ? <img className="hrm__photo" src={tabImage(cur)} alt="" /> : <div className="hrm__photo hm-img-empty">Chưa có ảnh — chọn ở thanh bên phải</div>}
									</div>
								) : (
									<p>Chưa có tab nào. Bấm "+ Thêm tab" ở thanh bên phải.</p>
								)}
							</div>
							<RichText tagName="p" className="hrm__note" value={note} onChange={(v) => setAttributes({ note: v })} placeholder="Ghi chú nhỏ" allowedFormats={[]} />
						</>
					) : (
						<div className="hrm__cards">
							{cards.map((c, i) => (
								<div className="hrm__card" key={i}>
									<RichText tagName="span" className="hrm__index" value={c.index} onChange={(v) => setCard(i, { index: v })} placeholder="01" allowedFormats={[]} />
									<div>
										<RichText tagName="p" className="hrm__days" value={c.days} onChange={(v) => setCard(i, { days: v })} placeholder="NGÀY 1–3" allowedFormats={[]} />
										<RichText tagName="h3" className="hrm__card-title" value={c.title} onChange={(v) => setCard(i, { title: v })} placeholder="Tiêu đề (Shift+Enter xuống dòng)" />
										<RichText tagName="span" className="hrm__detail" value={c.detail} onChange={(v) => setCard(i, { detail: v })} placeholder="Mô tả" />
									</div>
									<span className="hrm__card-cta">
										{cardCta} <b>↗︎</b>
									</span>
								</div>
							))}
						</div>
					)}
				</div>
			</section>
		</>
	);
}

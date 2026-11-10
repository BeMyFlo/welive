import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import { useState } from "@wordpress/element";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { badge, title, titleAccent, description, tabs = [], footnote, linkText, linkUrl } = attributes;
	const [active, setActive] = useState(0);
	const blockProps = useBlockProps({ className: "crm" });
	const set = (key) => (v) => setAttributes({ [key]: v });
	const current = Math.min(active, Math.max(tabs.length - 1, 0));
	const cur = tabs[current];

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Các tab lộ trình"
					hint="Bấm tab trên canvas để xem trước, sửa nội dung từng tab ở đây."
					items={tabs}
					setItems={(v) => setAttributes({ tabs: v })}
					active={current}
					setActive={setActive}
					itemLabel={(t) => `${dec(t.label)} ${dec(t.name)}`}
					makeItem={() => ({ label: "NGÀY ", name: "Tab mới", heading: "", bullets: [""], imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm tab"
					fields={[
						{ key: "label", label: "Ngày (nhãn nhỏ)" },
						{ key: "name", label: "Tên tab" },
						{ key: "heading", label: "Tiêu đề trong khung" },
						{ key: "bullets", label: "Các ý", type: "lines" },
						{ key: "imageId", type: "image", label: "Ảnh của tab này" },
					]}
				/>
				<PanelBody title="Liên kết" initialOpen={false}>
					<TextControl label="Anchor (id để link tới, vd #lo-trinh)" value={attributes.anchor || ""} onChange={set("anchor")} />
					<TextControl label="Link cuối khối" value={linkUrl} onChange={set("linkUrl")} help='Dùng "/duong-dan/" cho trang trong site.' />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="crm__container">
					<div className="crm__header">
						<div className="crm__intro">
							<RichText tagName="span" className="crm__badge" value={badge} onChange={set("badge")} placeholder="Nhãn" allowedFormats={[]} />
							<RichText tagName="h2" className="crm__title" value={title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
							<RichText tagName="p" className="crm__title crm__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Tiêu đề (dòng màu)" allowedFormats={[]} />
						</div>
						<RichText tagName="p" className="crm__desc" value={description} onChange={set("description")} placeholder="Mô tả" />
					</div>
					<div className="crm__tabs" role="tablist">
						{tabs.map((t, i) => (
							<div key={i} role="button" tabIndex={0} className={`crm__tab${i === current ? " is-active" : ""}`} onClick={() => setActive(i)} onKeyDown={(e) => (e.key === "Enter" || e.key === " ") && setActive(i)}>
								<Html className="crm__tab-label" value={t.label} />
								<Html className="crm__tab-name" value={t.name} />
							</div>
						))}
					</div>
					<div className="crm__panels">
						{cur ? (
							<div className="crm__panel is-active">
								<div className="crm__panel-text">
									<Html className="crm__panel-label" value={cur.label} />
									<Html tag="h3" className="crm__panel-heading" value={cur.heading} />
									<ul className="crm__list">
										{(cur.bullets || []).filter((b) => b.trim() !== "").map((b, j) => (
											<Html key={j} tag="li" value={b} />
										))}
									</ul>
								</div>
								<div className="crm__panel-media">{cur.imageUrl ? <img src={cur.imageUrl} alt="" /> : <span className="hm-img-empty">Chưa có ảnh — chọn ở thanh bên phải</span>}</div>
							</div>
						) : (
							<p>Chưa có tab nào. Bấm "+ Thêm tab" ở thanh bên phải.</p>
						)}
					</div>
					<div className="crm__footer">
						<RichText tagName="p" className="crm__footnote" value={footnote} onChange={set("footnote")} placeholder="Ghi chú nhỏ" allowedFormats={[]} />
						<span className="crm__link">
							<RichText tagName="span" value={linkText} onChange={set("linkText")} placeholder="Chữ link" allowedFormats={[]} />
						</span>
					</div>
				</div>
			</div>
		</>
	);
}

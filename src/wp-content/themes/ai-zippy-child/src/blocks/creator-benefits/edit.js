import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleLine2, description, cards = [] } = attributes;
	const blockProps = useBlockProps({ className: "cbn" });
	const set = (key) => (v) => setAttributes({ [key]: v });

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Thẻ quyền lợi"
					hint="Mỗi thẻ có ảnh, tiêu đề và các ý. Sửa ở đây, canvas bên trái là bản xem trước."
					items={cards}
					setItems={(v) => setAttributes({ cards: v })}
					itemLabel={(c) => dec(c.title)}
					makeItem={() => ({ title: "Quyền lợi mới", bullets: [""], imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm thẻ"
					fields={[
						{ key: "title", label: "Tiêu đề thẻ" },
						{ key: "bullets", label: "Các ý", type: "lines" },
						{ key: "imageId", type: "image", label: "Ảnh của thẻ" },
					]}
				/>
				<PanelBody title="Liên kết" initialOpen={false}>
					<TextControl label="Anchor (id để link tới, vd #quyen-loi)" value={attributes.anchor || ""} onChange={set("anchor")} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cbn__container">
					<div className="cbn__header">
						<div className="cbn__intro">
							<RichText tagName="span" className="cbn__eyebrow" value={eyebrow} onChange={set("eyebrow")} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<RichText tagName="h2" className="cbn__title" value={title} onChange={set("title")} placeholder="Tiêu đề dòng 1" allowedFormats={[]} />
							<RichText tagName="h2" className="cbn__title" value={titleLine2} onChange={set("titleLine2")} placeholder="Tiêu đề dòng 2" allowedFormats={[]} />
						</div>
						<RichText tagName="p" className="cbn__desc" value={description} onChange={set("description")} placeholder="Mô tả ngắn" />
					</div>
					<div className="cbn__grid">
						{cards.map((c, i) => (
							<div key={i} className="cbn__card">
								<div className="cbn__media">{c.imageUrl ? <img src={c.imageUrl} alt="" /> : <span className="hm-img-empty">Chưa có ảnh</span>}</div>
								<div className="cbn__body">
									<Html tag="h3" className="cbn__card-title" value={c.title} />
									<ul className="cbn__list">
										{(c.bullets || []).filter((b) => b.trim() !== "").map((b, j) => (
											<Html key={j} tag="li" value={b} />
										))}
									</ul>
								</div>
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}

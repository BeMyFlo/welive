import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleAccent, caption, alt, imageId, imageUrl, items = [] } = attributes;
	const blockProps = useBlockProps({ className: "abf" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Ảnh" initialOpen={true}>
					<p style={{ marginTop: 0, color: "#757575" }}>Bấm vào ảnh trên canvas để đổi ảnh.</p>
					<TextControl label="Alt ảnh (mô tả cho người khiếm thị)" value={alt || ""} onChange={(v) => setAttributes({ alt: v })} />
				</PanelBody>
				<ItemsPanel
					title="Các giá trị"
					items={items}
					setItems={(v) => setAttributes({ items: v })}
					itemLabel={(it) => dec(it.title)}
					makeItem={() => ({ title: "Giá trị mới", text: "" })}
					addLabel="+ Thêm giá trị"
					fields={[
						{ key: "title", label: "Tiêu đề" },
						{ key: "text", label: "Mô tả", type: "textarea", rows: 4 },
					]}
				/>
			</InspectorControls>
			<section {...blockProps}>
				<div className="abf__wrap">
					<figure className="abf__photo">
						<ImagePicker imageId={imageId} imageUrl={imageUrl} onSelect={(v) => setAttributes(v)} />
						<RichText tagName="figcaption" value={caption} onChange={(v) => setAttributes({ caption: v })} placeholder="Chú thích ảnh" allowedFormats={[]} />
					</figure>
					<div className="abf__copy">
						<RichText tagName="p" className="abf__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
						<h2 className="abf__title">
							<RichText tagName="span" style={{ display: "block", color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
							<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Tiêu đề phụ (màu xám)" allowedFormats={[]} />
						</h2>
						<ol className="abf__list">
							{items.map((it, i) => (
								<li key={i}>
									<span>{String(i + 1).padStart(2, "0")}</span>
									<div>
										<Html tag="h3" value={it.title} />
										<Html tag="p" value={it.text} />
									</div>
								</li>
							))}
						</ol>
					</div>
				</div>
			</section>
		</>
	);
}

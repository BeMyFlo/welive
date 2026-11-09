import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleAccent, caption, alt, imageId, imageUrl, items = [] } = attributes;
	const blockProps = useBlockProps({ className: "abf" });
	const setItem = (i, patch) => setAttributes({ items: items.map((it, k) => (k === i ? { ...it, ...patch } : it)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Ảnh" initialOpen={true}>
					<TextControl label="Alt ảnh" value={alt || ""} onChange={(v) => setAttributes({ alt: v })} />
				</PanelBody>
				<PanelBody title="Mục" initialOpen={false}>
					<Button variant="secondary" onClick={() => setAttributes({ items: [...items, { title: "", text: "" }] })}>
						+ Thêm mục
					</Button>
					{items.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ items: items.slice(0, -1) })}>
							Xoá mục cuối
						</Button>
					)}
				</PanelBody>
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
										<RichText tagName="h3" value={it.title} onChange={(v) => setItem(i, { title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
										<RichText tagName="p" value={it.text} onChange={(v) => setItem(i, { text: v })} placeholder="Mô tả" />
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

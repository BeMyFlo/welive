import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleAccent, text, btnText, btnUrl, caption, alt, imageId, imageUrl } = attributes;
	const blockProps = useBlockProps({ className: "acm" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút & ảnh" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #cong-dong)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link nút" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
					<TextControl label="Alt ảnh" value={alt || ""} onChange={(v) => setAttributes({ alt: v })} />
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="acm__wrap">
					<div className="acm__photo">
						<ImagePicker imageId={imageId} imageUrl={imageUrl} onSelect={(v) => setAttributes(v)} />
						<RichText tagName="span" className="acm__caption" value={caption} onChange={(v) => setAttributes({ caption: v })} placeholder="Chú thích ảnh" allowedFormats={[]} />
					</div>
					<div className="acm__copy">
						<RichText tagName="p" className="acm__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
						<h2 className="acm__title">
							<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" />{" "}
							<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Phần nhấn" allowedFormats={[]} />
						</h2>
						<RichText tagName="p" className="acm__text" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Mô tả" />
						<span className="acm__button-mock">
							<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Nút" allowedFormats={[]} /> ↗︎
						</span>
					</div>
				</div>
			</section>
		</>
	);
}

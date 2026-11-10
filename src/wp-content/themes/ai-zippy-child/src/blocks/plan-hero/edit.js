import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { pill, title, titleAccent, text, ctaText, ctaUrl, linkText, linkUrl, note, image, captionTitle, captionText } = attributes;
	const blockProps = useBlockProps({ className: "phr" });
	const photo = image || {};
	const set = (key) => (v) => setAttributes({ [key]: v });
	const updatePhoto = (patch) => setAttributes({ image: { ...photo, ...patch } });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Liên kết" initialOpen={true}>
					<TextControl label="Anchor (id để link tới)" value={attributes.anchor || ""} onChange={set("anchor")} />
					<TextControl label="Link nút chính" value={ctaUrl} onChange={set("ctaUrl")} />
					<TextControl label="Link phụ" value={linkUrl} onChange={set("linkUrl")} />
				</PanelBody>
				<PanelBody title="Ảnh" initialOpen={false}>
					<TextControl label="Alt ảnh" value={photo.alt || ""} onChange={(v) => updatePhoto({ alt: v })} />
					<TextControl label="Vị trí ảnh (object-position)" value={photo.position || ""} onChange={(v) => updatePhoto({ position: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="phr__wrap">
					<div className="phr__copy">
						<RichText tagName="span" className="phr__pill" value={pill} onChange={set("pill")} placeholder="Nhãn" allowedFormats={[]} />
						<RichText tagName="h1" className="phr__title" value={title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" className="phr__accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Dòng màu cam" allowedFormats={[]} />
						<RichText tagName="p" className="phr__text" value={text} onChange={set("text")} placeholder="Mô tả" />
						<div className="phr__actions">
							<span className="phr__cta"><RichText tagName="span" value={ctaText} onChange={set("ctaText")} placeholder="Nút" allowedFormats={[]} /></span>
							<span className="phr__link"><RichText tagName="span" value={linkText} onChange={set("linkText")} placeholder="Link phụ" allowedFormats={[]} /></span>
						</div>
						<RichText tagName="p" className="phr__note" value={note} onChange={set("note")} placeholder="Ghi chú" allowedFormats={[]} />
					</div>
					<div className="phr__visual">
						<ImagePicker className="phr__photo-edit" imageId={photo.imageId} imageUrl={photo.imageUrl} onSelect={updatePhoto} />
						<div className="phr__caption">
							<RichText tagName="strong" value={captionTitle} onChange={set("captionTitle")} placeholder="Tiêu đề chú thích" allowedFormats={[]} />
							<RichText tagName="span" value={captionText} onChange={set("captionText")} placeholder="Chú thích" allowedFormats={[]} />
						</div>
					</div>
				</div>
			</section>
		</>
	);
}

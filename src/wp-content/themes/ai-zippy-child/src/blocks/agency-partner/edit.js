import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { image, title, text, text2, btnText, btnUrl } = attributes;
	const blockProps = useBlockProps({ className: "agp" });
	const photo = image || {};
	const updatePhoto = (patch) => setAttributes({ image: { ...photo, ...patch } });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #hop-tac-agency)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link nút" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
					<TextControl label="Alt ảnh" value={photo.alt || ""} onChange={(v) => updatePhoto({ alt: v })} />
					<TextControl label="Vị trí ảnh (object-position, vd 50% 50%)" value={photo.position || ""} onChange={(v) => updatePhoto({ position: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="agp__wrap">
					<ImagePicker className="agp__photo" imageId={photo.imageId} imageUrl={photo.imageUrl} onSelect={updatePhoto} />
					<div className="agp__copy">
						<RichText tagName="h2" className="agp__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Đoạn 1" />
						<RichText tagName="p" value={text2} onChange={(v) => setAttributes({ text2: v })} placeholder="Đoạn 2" />
						<span className="agp__btn">
							<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Chữ nút" allowedFormats={[]} />
						</span>
					</div>
				</div>
			</section>
		</>
	);
}

import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, text, btnText, btnUrl, newTab } = attributes;
	const blockProps = useBlockProps({ className: "agc" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #lien-he)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link nút" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
					<ToggleControl label="Mở tab mới" checked={!!newTab} onChange={(v) => setAttributes({ newTab: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="agc__wrap">
					<div className="agc__copy">
						<RichText tagName="h2" className="agc__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Mô tả" />
					</div>
					<span className="agc__btn">
						<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Chữ nút" allowedFormats={[]} />
					</span>
				</div>
			</section>
		</>
	);
}

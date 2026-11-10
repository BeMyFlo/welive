import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, address, linkText, linkUrl } = attributes;
	const blockProps = useBlockProps({ className: "cof" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Link" initialOpen={true}>
					<TextControl label="Anchor (id để link tới)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link chỉ đường" value={linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="cof__wrap">
					<div>
						<RichText tagName="h2" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tên văn phòng" allowedFormats={[]} />
						<RichText tagName="address" value={address} onChange={(v) => setAttributes({ address: v })} placeholder="Địa chỉ" allowedFormats={[]} />
					</div>
					<RichText tagName="span" className="cof__link" value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Chữ link" allowedFormats={[]} />
				</div>
			</section>
		</>
	);
}

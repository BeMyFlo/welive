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
					<TextControl
						label="Link nhúng bản đồ (Google Maps → Chia sẻ → Nhúng bản đồ → chép phần src, bắt đầu bằng https://www.google.com/maps/embed)"
						value={attributes.mapUrl || ""}
						onChange={(v) => setAttributes({ mapUrl: v })}
					/>
					<TextControl label="Mô tả bản đồ (cho trình đọc màn hình)" value={attributes.mapTitle || ""} onChange={(v) => setAttributes({ mapTitle: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="cof__wrap">
					<div>
						<RichText tagName="h2" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tên văn phòng" allowedFormats={[]} />
						<RichText tagName="address" value={address} onChange={(v) => setAttributes({ address: v })} placeholder="Địa chỉ" allowedFormats={[]} />
					</div>
					<RichText tagName="span" className="cof__link" value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Chữ link" allowedFormats={[]} />
					{attributes.mapUrl ? <div className="cof__map cof__map--edit">Bản đồ Google Maps (hiện ở trang thật)</div> : null}
				</div>
			</section>
		</>
	);
}

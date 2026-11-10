import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { text, phoneTitle, phone, phonePlaceholder, phoneNote } = attributes;
	const blockProps = useBlockProps({ className: "cin" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Số điện thoại" initialOpen={true}>
					<TextControl label="Anchor (id để link tới)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Số Zalo (bắt đầu bằng 0 hoặc +84; để trống nếu chưa có)" value={phone} onChange={(v) => setAttributes({ phone: v })} />
					<TextControl label="Chữ khi chưa có số" value={phonePlaceholder} onChange={(v) => setAttributes({ phonePlaceholder: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="cin__wrap">
					<div className="cin__text">
						<RichText tagName="p" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Đoạn giới thiệu" />
					</div>
					<aside className="cin__phone">
						<RichText tagName="h2" value={phoneTitle} onChange={(v) => setAttributes({ phoneTitle: v })} placeholder="Tiêu đề thẻ" allowedFormats={[]} />
						<RichText tagName="p" className="cin__value" value={phone} onChange={(v) => setAttributes({ phone: v })} placeholder={phonePlaceholder || "Số điện thoại"} allowedFormats={[]} />
						<RichText tagName="p" className="cin__note" value={phoneNote} onChange={(v) => setAttributes({ phoneNote: v })} placeholder="Ghi chú" allowedFormats={[]} />
					</aside>
				</div>
			</section>
		</>
	);
}

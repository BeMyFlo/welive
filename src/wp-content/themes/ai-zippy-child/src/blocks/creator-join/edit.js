import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, text, btnText, btnUrl, artText } = attributes;
	const blockProps = useBlockProps({ className: "cjn" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #gia-nhap)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="cjn__wrap">
					<div className="cjn__copy">
						<RichText tagName="p" className="cjn__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
						<RichText tagName="h2" className="cjn__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề (Shift+Enter xuống dòng)" />
						<RichText tagName="p" className="cjn__text" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Mô tả" />
						<span className="cjn__button-mock">
							<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Nút" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<div className="cjn__art">
						<RichText tagName="span" className="cjn__art-text" value={artText} onChange={(v) => setAttributes({ artText: v })} placeholder="Chữ lớn bên phải" />
						<span className="cjn__arrow">↗︎</span>
					</div>
				</div>
			</section>
		</>
	);
}

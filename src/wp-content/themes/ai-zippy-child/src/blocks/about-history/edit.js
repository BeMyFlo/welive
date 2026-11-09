import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, anchor, items = [] } = attributes;
	const blockProps = useBlockProps({ className: "ahy" });
	const setItem = (i, patch) => setAttributes({ items: items.map((it, k) => (k === i ? { ...it, ...patch } : it)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Mốc thời gian" initialOpen={true}>
					<TextControl label="Anchor (id)" value={anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<Button variant="secondary" onClick={() => setAttributes({ items: [...items, { year: "", text: "" }] })}>
						+ Thêm mốc
					</Button>
					{items.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ items: items.slice(0, -1) })}>
							Xoá mốc cuối
						</Button>
					)}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="ahy__wrap">
					<RichText tagName="p" className="ahy__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
					<RichText tagName="h2" className="ahy__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
					<ol className="ahy__list">
						{items.map((it, i) => (
							<li key={i}>
								<RichText tagName="span" className="ahy__year" value={it.year} onChange={(v) => setItem(i, { year: v })} placeholder="2026" allowedFormats={[]} />
								<RichText tagName="p" value={it.text} onChange={(v) => setItem(i, { text: v })} placeholder="Nội dung (Shift+Enter xuống dòng)" />
							</li>
						))}
					</ol>
				</div>
			</section>
		</>
	);
}

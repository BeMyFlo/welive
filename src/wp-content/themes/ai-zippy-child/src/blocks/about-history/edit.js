import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, items = [] } = attributes;
	const blockProps = useBlockProps({ className: "ahy" });

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Các mốc thời gian"
					hint="Mốc cuối cùng được tô màu cam. Thêm, xoá, đổi thứ tự ở đây."
					items={items}
					setItems={(v) => setAttributes({ items: v })}
					itemLabel={(it) => `${dec(it.year)} — ${dec(it.text).slice(0, 40)}`}
					makeItem={() => ({ year: "2027", text: "" })}
					addLabel="+ Thêm mốc"
					fields={[
						{ key: "year", label: "Năm" },
						{ key: "text", label: "Nội dung", type: "textarea", rows: 3 },
					]}
				/>
				<PanelBody title="Liên kết" initialOpen={false}>
					<TextControl label="Anchor (id để link tới, vd #hanh-trinh)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="ahy__wrap">
					<RichText tagName="p" className="ahy__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
					<RichText tagName="h2" className="ahy__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
					<ol className="ahy__list">
						{items.map((it, i) => (
							<li key={i}>
								<Html className="ahy__year" value={it.year} />
								<Html tag="p" value={it.text} />
							</li>
						))}
					</ol>
				</div>
			</section>
		</>
	);
}

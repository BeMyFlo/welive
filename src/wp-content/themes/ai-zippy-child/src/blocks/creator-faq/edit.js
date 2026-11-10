import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleLine2, description, items = [] } = attributes;
	const blockProps = useBlockProps({ className: "cfq" });
	const set = (key) => (v) => setAttributes({ [key]: v });

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Câu hỏi thường gặp"
					hint="Mỗi mục gồm một câu hỏi và câu trả lời. Sửa ở đây, canvas là bản xem trước."
					items={items}
					setItems={(v) => setAttributes({ items: v })}
					itemLabel={(it) => dec(it.q)}
					makeItem={() => ({ q: "Câu hỏi mới?", a: "" })}
					addLabel="+ Thêm câu hỏi"
					fields={[
						{ key: "q", label: "Câu hỏi" },
						{ key: "a", label: "Câu trả lời", type: "textarea", rows: 6 },
					]}
				/>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cfq__container">
					<div className="cfq__intro">
						<RichText tagName="span" className="cfq__eyebrow" value={eyebrow} onChange={set("eyebrow")} placeholder="Nhãn nhỏ" allowedFormats={[]} />
						<RichText tagName="h2" className="cfq__title" value={title} onChange={set("title")} placeholder="Tiêu đề dòng 1" allowedFormats={[]} />
						<RichText tagName="h2" className="cfq__title" value={titleLine2} onChange={set("titleLine2")} placeholder="Tiêu đề dòng 2" allowedFormats={[]} />
						<RichText tagName="p" className="cfq__desc" value={description} onChange={set("description")} placeholder="Mô tả ngắn" />
					</div>
					<div className="cfq__list">
						{items.map((it, i) => (
							<details key={i} className="cfq__item" open={i === 0}>
								<summary className="cfq__q">
									<Html value={it.q} />
									<span className="cfq__icon" aria-hidden="true"></span>
								</summary>
								<Html tag="div" className="cfq__a" value={it.a} />
							</details>
						))}
					</div>
				</div>
			</div>
		</>
	);
}

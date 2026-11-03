import { useBlockProps, RichText } from "@wordpress/block-editor";
import { Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleLine2, description, items } = attributes;
	const blockProps = useBlockProps({ className: "cfq" });
	const set = (key) => (val) => setAttributes({ [key]: val });
	const update = (index, patch) => setAttributes({ items: items.map((x, i) => (i === index ? { ...x, ...patch } : x)) });
	const remove = (index) => setAttributes({ items: items.filter((_, i) => i !== index) });
	const add = () => setAttributes({ items: [...items, { q: "Câu hỏi mới?", a: "Câu trả lời." }] });

	return (
		<div {...blockProps}>
			<div className="cfq__container">
				<div className="cfq__intro">
					<RichText tagName="span" className="cfq__eyebrow" value={eyebrow} onChange={set("eyebrow")} placeholder="Eyebrow..." allowedFormats={[]} />
					<RichText tagName="h2" className="cfq__title" value={title} onChange={set("title")} placeholder="Title line 1..." allowedFormats={[]} />
					<RichText tagName="h2" className="cfq__title" value={titleLine2} onChange={set("titleLine2")} placeholder="Title line 2..." allowedFormats={[]} />
					<RichText tagName="p" className="cfq__desc" value={description} onChange={set("description")} placeholder="Description..." allowedFormats={[]} />
				</div>
				<div className="cfq__list">
					{items.map((item, index) => (
						<div key={index} className="cfq__item is-open">
							<div className="cfq__q">
								<RichText tagName="span" value={item.q} onChange={(val) => update(index, { q: val })} placeholder="Câu hỏi" allowedFormats={[]} />
								<Button variant="link" isDestructive onClick={() => remove(index)}>Xoá</Button>
							</div>
							<RichText tagName="div" className="cfq__a" value={item.a} onChange={(val) => update(index, { a: val })} placeholder="Câu trả lời" allowedFormats={[]} />
						</div>
					))}
					<Button variant="secondary" onClick={add} style={{ marginTop: 16 }}>+ Thêm câu hỏi</Button>
				</div>
			</div>
		</div>
	);
}

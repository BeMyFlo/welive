import { RichText } from "@wordpress/block-editor";

// Editable list of plain-text bullets with add / remove controls (editor canvas only).
export default function BulletsEditor({ items = [], onChange, listClass, itemClass, placeholder = "Nội dung..." }) {
	const set = (index, value) => onChange(items.map((text, i) => (i === index ? value : text)));
	const remove = (index) => onChange(items.filter((_, i) => i !== index));

	return (
		<>
			<ul className={listClass}>
				{items.map((text, index) => (
					<li key={index} className={itemClass}>
						<RichText tagName="span" value={text} onChange={(val) => set(index, val)} placeholder={placeholder} allowedFormats={[]} />
						<button type="button" className="az-bullet-remove" onClick={() => remove(index)} aria-label="Xoá">×</button>
					</li>
				))}
			</ul>
			<button type="button" className="az-bullet-add" onClick={() => onChange([...items, ""])}>+ Thêm ý</button>
		</>
	);
}

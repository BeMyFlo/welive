import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, RangeControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { badge, title, titleAccent, items, placeholderCount, placeholderTitle, placeholderText, footnote } = attributes;
	const blockProps = useBlockProps({ className: "cwl" });
	const set = (key) => (val) => setAttributes({ [key]: val });
	const update = (index, patch) => setAttributes({ items: items.map((c, i) => (i === index ? { ...c, ...patch } : c)) });
	const remove = (index) => setAttributes({ items: items.filter((_, i) => i !== index) });
	const add = () => setAttributes({ items: [...items, { name: "TÊN", linkText: "Xem câu chuyện", linkUrl: "", imageId: 0, imageUrl: "" }] });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Creator links" initialOpen={true}>
					{items.map((item, index) => (
						<TextControl key={index} label={`Link: ${item.name || "#" + (index + 1)}`} value={item.linkUrl} onChange={(val) => update(index, { linkUrl: val })} help={index === 0 ? 'Để trống nếu thẻ không bấm được. Dùng "/duong-dan/" cho trang trong site.' : undefined} />
					))}
				</PanelBody>
				<PanelBody title="Placeholder cards" initialOpen={false}>
					<RangeControl label="Number of placeholder cards" value={placeholderCount} onChange={set("placeholderCount")} min={0} max={6} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cwl__container">
					<div className="cwl__header">
						<div className="cwl__intro">
							<span className="cwl__badge"><RichText tagName="span" value={badge} onChange={set("badge")} placeholder="Badge..." allowedFormats={[]} /></span>
							<h2 className="cwl__title">
								<RichText tagName="span" value={title} onChange={set("title")} placeholder="Title..." allowedFormats={[]} />
								<RichText tagName="span" className="cwl__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Accent..." allowedFormats={[]} />
							</h2>
						</div>
					</div>
					<div className="cwl__track">
						{items.map((item, index) => (
							<div key={index} className="cwl__card">
								<ImagePicker className="cwl__card-img" imageId={item.imageId} imageUrl={item.imageUrl} onSelect={(patch) => update(index, patch)} />
								<div className="cwl__card-info">
									<RichText tagName="strong" className="cwl__card-name" value={item.name} onChange={(val) => update(index, { name: val })} placeholder="Name" allowedFormats={[]} />
									<RichText tagName="span" className="cwl__card-link" value={item.linkText} onChange={(val) => update(index, { linkText: val })} placeholder="Link text" allowedFormats={[]} />
									<Button variant="link" isDestructive onClick={() => remove(index)}>Xoá</Button>
								</div>
							</div>
						))}
						<div className="cwl__card cwl__card--placeholder">
							<span className="cwl__mark">W.</span>
							<div className="cwl__card-info">
								<RichText tagName="strong" className="cwl__card-name" value={placeholderTitle} onChange={set("placeholderTitle")} placeholder="Placeholder title" allowedFormats={[]} />
								<RichText tagName="span" className="cwl__card-link" value={placeholderText} onChange={set("placeholderText")} placeholder="Placeholder text" allowedFormats={[]} />
							</div>
						</div>
					</div>
					<Button variant="secondary" onClick={add} style={{ marginTop: 16 }}>+ Thêm creator</Button>
					<RichText tagName="p" className="cwl__footnote" value={footnote} onChange={set("footnote")} placeholder="Ghi chú..." allowedFormats={[]} />
				</div>
			</div>
		</>
	);
}

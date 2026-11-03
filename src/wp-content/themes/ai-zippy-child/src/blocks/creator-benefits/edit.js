import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";
import BulletsEditor from "../shared/BulletsEditor.js";

export default function Edit({ attributes, setAttributes }) {
	const { anchor, eyebrow, title, titleLine2, description, cards } = attributes;
	const blockProps = useBlockProps({ className: "cbn" });
	const set = (key) => (val) => setAttributes({ [key]: val });
	const update = (index, patch) => setAttributes({ cards: cards.map((c, i) => (i === index ? { ...c, ...patch } : c)) });
	const remove = (index) => setAttributes({ cards: cards.filter((_, i) => i !== index) });
	const add = () => setAttributes({ cards: [...cards, { title: "Tiêu đề", bullets: ["Nội dung"], imageId: 0, imageUrl: "" }] });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={true}>
					<TextControl label="Anchor (id for #links)" value={anchor} onChange={set("anchor")} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cbn__container">
					<div className="cbn__header">
						<div className="cbn__intro">
							<RichText tagName="span" className="cbn__eyebrow" value={eyebrow} onChange={set("eyebrow")} placeholder="Eyebrow..." allowedFormats={[]} />
							<RichText tagName="h2" className="cbn__title" value={title} onChange={set("title")} placeholder="Title line 1..." allowedFormats={[]} />
							<RichText tagName="h2" className="cbn__title" value={titleLine2} onChange={set("titleLine2")} placeholder="Title line 2..." allowedFormats={[]} />
						</div>
						<RichText tagName="p" className="cbn__desc" value={description} onChange={set("description")} placeholder="Description..." allowedFormats={[]} />
					</div>
					<div className="cbn__grid">
						{cards.map((card, index) => (
							<div key={index} className="cbn__card">
								<ImagePicker className="cbn__media" imageId={card.imageId} imageUrl={card.imageUrl} onSelect={(patch) => update(index, patch)} />
								<div className="cbn__body">
									<RichText tagName="h3" className="cbn__card-title" value={card.title} onChange={(val) => update(index, { title: val })} placeholder="Title" allowedFormats={[]} />
									<BulletsEditor items={card.bullets} onChange={(val) => update(index, { bullets: val })} listClass="cbn__list" />
									<Button variant="link" isDestructive onClick={() => remove(index)}>Xoá thẻ</Button>
								</div>
							</div>
						))}
					</div>
					<Button variant="secondary" onClick={add} style={{ marginTop: 20 }}>+ Thêm thẻ</Button>
				</div>
			</div>
		</>
	);
}

import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";
import AgencyIcon, { ICON_OPTIONS } from "../shared/AgencyIcon.js";

export default function Edit({ attributes, setAttributes }) {
	const { anchor, title, description, cards, btnText, btnUrl } = attributes;
	const blockProps = useBlockProps({ className: "ags" });
	const update = (index, patch) => setAttributes({ cards: cards.map((c, i) => (i === index ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={false}>
					<TextControl label="Anchor (id for #links)" value={anchor} onChange={(val) => setAttributes({ anchor: val })} />
					<TextControl label="Button link" value={btnUrl} onChange={(val) => setAttributes({ btnUrl: val })} />
				</PanelBody>
				<PanelBody title="Card icons" initialOpen={true}>
					{cards.map((card, index) => (
						<SelectControl key={index} label={`Card #${index + 1}`} value={card.icon} options={ICON_OPTIONS.services} onChange={(val) => update(index, { icon: val })} />
					))}
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="ags__container">
					<div className="ags__header">
						<RichText tagName="h2" className="ags__title" value={title} onChange={(val) => setAttributes({ title: val })} placeholder="Title..." allowedFormats={[]} />
						<RichText tagName="p" className="ags__desc" value={description} onChange={(val) => setAttributes({ description: val })} placeholder="Description..." allowedFormats={[]} />
					</div>
					<div className="ags__grid">
						{cards.map((card, index) => (
							<div key={index} className="ags__card">
								<span className="ags__icon"><AgencyIcon name={card.icon} /></span>
								<RichText tagName="h3" className="ags__card-title" value={card.title} onChange={(val) => update(index, { title: val })} placeholder="Title" allowedFormats={[]} />
								<RichText tagName="p" className="ags__card-subtitle" value={card.subtitle} onChange={(val) => update(index, { subtitle: val })} placeholder="Subtitle" allowedFormats={[]} />
								<RichText tagName="p" className="ags__card-text" value={card.text} onChange={(val) => update(index, { text: val })} placeholder="Text" allowedFormats={[]} />
								<ImagePicker className="ags__media" imageId={card.imageId} imageUrl={card.imageUrl} onSelect={(patch) => update(index, patch)} />
							</div>
						))}
					</div>
					<div className="ags__cta">
						<span className="ags__btn">
							<RichText tagName="span" value={btnText} onChange={(val) => setAttributes({ btnText: val })} placeholder="Button text" allowedFormats={[]} />
						</span>
					</div>
				</div>
			</div>
		</>
	);
}

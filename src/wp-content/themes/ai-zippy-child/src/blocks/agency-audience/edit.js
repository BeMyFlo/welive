import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { cards } = attributes;
	const blockProps = useBlockProps({ className: "aga" });
	const update = (index, patch) => setAttributes({ cards: cards.map((c, i) => (i === index ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				{cards.map((card, index) => (
					<PanelBody key={index} title={`Card #${index + 1}`} initialOpen={index === 0}>
						<TextControl label="Button link" value={card.btnUrl} onChange={(val) => update(index, { btnUrl: val })} />
						<SelectControl
							label="Style"
							value={card.tone}
							options={[
								{ label: "Dark", value: "dark" },
								{ label: "Warm (orange tint)", value: "warm" },
							]}
							onChange={(val) => update(index, { tone: val })}
						/>
					</PanelBody>
				))}
			</InspectorControls>
			<div {...blockProps}>
				<div className="aga__container">
					{cards.map((card, index) => (
						<div key={index} className={`aga__card aga__card--${card.tone === "warm" ? "warm" : "dark"}`}>
							<div className="aga__body">
								<RichText tagName="h2" className="aga__title" value={card.title} onChange={(val) => update(index, { title: val })} placeholder="Title..." allowedFormats={[]} />
								<RichText tagName="p" className="aga__subtitle" value={card.subtitle} onChange={(val) => update(index, { subtitle: val })} placeholder="Subtitle..." allowedFormats={[]} />
								<span className="aga__btn">
									<RichText tagName="span" value={card.btnText} onChange={(val) => update(index, { btnText: val })} placeholder="Button text" allowedFormats={[]} />
								</span>
							</div>
							<ImagePicker className="aga__media" imageId={card.imageId} imageUrl={card.imageUrl} onSelect={(patch) => update(index, patch)} />
						</div>
					))}
				</div>
			</div>
		</>
	);
}

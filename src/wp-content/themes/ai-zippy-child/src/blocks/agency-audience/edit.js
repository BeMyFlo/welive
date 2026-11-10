import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

const isDemoTone = (tone) => tone === "light" || tone === "orange";

export default function Edit({ attributes, setAttributes }) {
	const { cards } = attributes;
	const demo = cards.some((c) => isDemoTone(c.tone));
	const blockProps = useBlockProps({ className: demo ? "aga aga--demo" : "aga" });
	const update = (index, patch) => setAttributes({ cards: cards.map((c, i) => (i === index ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={false}>
					<TextControl label="Anchor (id để link tới, vd #doi-tac)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
				</PanelBody>
				{cards.map((card, index) => (
					<PanelBody key={index} title={`Thẻ ${index + 1}`} initialOpen={index === 0}>
						<TextControl label="Link nút" value={card.btnUrl} onChange={(val) => update(index, { btnUrl: val })} />
						<SelectControl
							label="Style"
							value={card.tone}
							options={[
								{ label: "Light (kem)", value: "light" },
								{ label: "Orange (cam)", value: "orange" },
								{ label: "Dark (cũ)", value: "dark" },
								{ label: "Warm (cũ, cam nhạt)", value: "warm" },
							]}
							onChange={(val) => update(index, { tone: val })}
						/>
					</PanelBody>
				))}
			</InspectorControls>
			<div {...blockProps}>
				<div className="aga__container">
					{cards.map((card, index) => (
						<div key={index} className={`aga__card aga__card--${card.tone || "dark"}`}>
							<div className="aga__body">
								{isDemoTone(card.tone) && (
									<RichText tagName="p" className="aga__eyebrow" value={card.eyebrow || ""} onChange={(val) => update(index, { eyebrow: val })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
								)}
								<RichText tagName="h2" className="aga__title" value={card.title} onChange={(val) => update(index, { title: val })} placeholder="Title..." allowedFormats={[]} />
								{isDemoTone(card.tone) ? (
									<RichText tagName="p" className="aga__text" value={card.text || ""} onChange={(val) => update(index, { text: val })} placeholder="Mô tả" allowedFormats={[]} />
								) : (
									<RichText tagName="p" className="aga__subtitle" value={card.subtitle} onChange={(val) => update(index, { subtitle: val })} placeholder="Subtitle..." allowedFormats={[]} />
								)}
								<span className="aga__btn">
									<RichText tagName="span" value={card.btnText} onChange={(val) => update(index, { btnText: val })} placeholder="Button text" allowedFormats={[]} />
								</span>
							</div>
							{!isDemoTone(card.tone) && <ImagePicker className="aga__media" imageId={card.imageId} imageUrl={card.imageUrl} onSelect={(patch) => update(index, patch)} />}
						</div>
					))}
				</div>
			</div>
		</>
	);
}

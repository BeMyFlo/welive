import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import AgencyIcon, { ICON_OPTIONS } from "../shared/AgencyIcon.js";

export default function Edit({ attributes, setAttributes }) {
	const { anchor, title, description, items } = attributes;
	const blockProps = useBlockProps({ className: "agc" });
	const update = (index, patch) => setAttributes({ items: items.map((c, i) => (i === index ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={false}>
					<TextControl label="Anchor (id for #links)" value={anchor} onChange={(val) => setAttributes({ anchor: val })} />
				</PanelBody>
				{items.map((item, index) => (
					<PanelBody key={index} title={`Item #${index + 1}`} initialOpen={index === 0}>
						<SelectControl label="Icon" value={item.icon} options={ICON_OPTIONS.contact} onChange={(val) => update(index, { icon: val })} />
						<TextControl label="Link (mailto:, tel:, https:// — optional)" value={item.url} onChange={(val) => update(index, { url: val })} />
					</PanelBody>
				))}
			</InspectorControls>
			<div {...blockProps}>
				<div className="agc__container">
					<div className="agc__header">
						<RichText tagName="h2" className="agc__title" value={title} onChange={(val) => setAttributes({ title: val })} placeholder="Title..." allowedFormats={[]} />
						<RichText tagName="p" className="agc__desc" value={description} onChange={(val) => setAttributes({ description: val })} placeholder="Description..." allowedFormats={[]} />
					</div>
					<div className="agc__grid">
						{items.map((item, index) => (
							<div key={index} className="agc__item">
								<span className="agc__icon"><AgencyIcon name={item.icon} /></span>
								<div className="agc__info">
									<RichText tagName="span" className="agc__label" value={item.label} onChange={(val) => update(index, { label: val })} placeholder="Label" allowedFormats={[]} />
									<RichText tagName="span" className="agc__value" value={item.value} onChange={(val) => update(index, { value: val })} placeholder="Value" allowedFormats={[]} />
								</div>
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}

import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { badge, title, titleAccent, description, primaryText, primaryUrl, secondaryText, secondaryUrl, metaText, imageId, imageUrl, captionTitle, captionText } = attributes;
	const blockProps = useBlockProps({ className: "cin" });
	const set = (key) => (val) => setAttributes({ [key]: val });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Links" initialOpen={true}>
					<TextControl label="Main button link" value={primaryUrl} onChange={set("primaryUrl")} help='Dùng "/duong-dan/" cho trang trong site.' />
					<TextControl label="Second link" value={secondaryUrl} onChange={set("secondaryUrl")} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cin__container">
					<div className="cin__text">
						<span className="cin__badge"><RichText tagName="span" value={badge} onChange={set("badge")} placeholder="Badge..." allowedFormats={[]} /></span>
						<h2 className="cin__title">
							<RichText tagName="span" value={title} onChange={set("title")} placeholder="Title..." allowedFormats={[]} />{" "}
							<RichText tagName="span" className="cin__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Accent..." allowedFormats={[]} />
						</h2>
						<RichText tagName="p" className="cin__desc" value={description} onChange={set("description")} placeholder="Description..." />
						<div className="cin__actions">
							<span className="cin__btn"><RichText tagName="span" value={primaryText} onChange={set("primaryText")} placeholder="Button..." allowedFormats={[]} /></span>
							<span className="cin__link"><RichText tagName="span" value={secondaryText} onChange={set("secondaryText")} placeholder="Link..." allowedFormats={[]} /></span>
						</div>
						<p className="cin__meta"><RichText tagName="span" value={metaText} onChange={set("metaText")} placeholder="Meta..." allowedFormats={[]} /></p>
					</div>
					<div className="cin__media">
						<ImagePicker className="cin__picker" imageId={imageId} imageUrl={imageUrl} onSelect={(patch) => setAttributes(patch)} />
						<div className="cin__caption">
							<RichText tagName="strong" value={captionTitle} onChange={set("captionTitle")} placeholder="Caption title" allowedFormats={[]} />
							<RichText tagName="span" value={captionText} onChange={set("captionText")} placeholder="Caption text" allowedFormats={[]} />
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

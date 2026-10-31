import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import CardsEditor from "../shared/CardsEditor.js";

export default function Edit({ attributes, setAttributes }) {
	const { sectionSubheading, sectionHeading, sectionDesc, bgImageId, bgImageUrl, textAlign, cards } = attributes;
	
	const blockProps = useBlockProps({ 
		className: `sg ${bgImageUrl ? "sg--has-bg" : ""} has-text-align-${textAlign || "left"}`,
		style: {
			backgroundImage: bgImageUrl ? `url(${bgImageUrl})` : "none"
		}
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title="Text Alignment" initialOpen={false}>
					<SelectControl
						label="Text Alignment"
						value={textAlign || "left"}
						options={[
							{ label: "Left", value: "left" },
							{ label: "Center", value: "center" },
							{ label: "Right", value: "right" },
							{ label: "Justify", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
				<PanelBody title="Background Image" initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ bgImageId: media.id, bgImageUrl: media.url })}
							allowedTypes={["image"]}
							value={bgImageId}
							render={({ open }) => (
								<Button onClick={open} variant="primary" style={{ width: "100%" }}>
									{bgImageUrl ? "Change Background" : "Select Background"}
								</Button>
							)}
						/>
						{bgImageUrl && (
							<Button onClick={() => setAttributes({ bgImageId: 0, bgImageUrl: "" })} variant="secondary" isDestructive style={{ width: "100%", marginTop: "10px" }}>
								Remove Background
							</Button>
						)}
					</MediaUploadCheck>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="sg__container">
					<div className="sg__layout">
					<div className="sg__intro">
						<div className="sg__subheading-wrap">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ transform: "rotate(45deg)", marginRight: 8 }}>
								<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
							</svg>
							<RichText
								tagName="h6"
								className="sg__subheading"
								value={sectionSubheading}
								onChange={(val) => setAttributes({ sectionSubheading: val })}
								placeholder="Subheading..."
							/>
						</div>
						<RichText
							tagName="h2"
							className="sg__heading"
							value={sectionHeading}
							onChange={(val) => setAttributes({ sectionHeading: val })}
							placeholder="Heading..."
						/>
						<RichText
							tagName="p"
							className="sg__desc"
							value={sectionDesc}
							onChange={(val) => setAttributes({ sectionDesc: val })}
							placeholder="Description..."
						/>
					</div>
					<CardsEditor prefix="sg" cards={cards} onChange={(val) => setAttributes({ cards: val })} />
					</div>
				</div>
			</div>
		</>
	);
}

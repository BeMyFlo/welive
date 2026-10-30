import { useBlockProps, RichText, MediaUpload, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { subheading, heading, description, buttonText, buttonUrl, bannerImageId, bannerImageUrl, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `ap has-text-align-${textAlign || "left"}` });

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
				<PanelBody title="CTA Button" initialOpen={true}>
					<TextControl
						label="Button Text"
						value={buttonText}
						onChange={(val) => setAttributes({ buttonText: val })}
					/>
					<TextControl
						label="Button URL"
						value={buttonUrl}
						onChange={(val) => setAttributes({ buttonUrl: val })}
					/>
				</PanelBody>
				<PanelBody title="Banner Image" initialOpen={true}>
					<MediaUpload
						onSelect={(media) => setAttributes({ bannerImageId: media.id, bannerImageUrl: media.url })}
						allowedTypes={["image"]}
						value={bannerImageId}
						render={({ open }) => (
							<div>
								{bannerImageUrl && (
									<img
										src={bannerImageUrl}
										alt="Banner"
										style={{ width: "100%", borderRadius: 4, marginBottom: 8 }}
									/>
								)}
								<Button variant="secondary" onClick={open} style={{ width: "100%" }}>
									{bannerImageUrl ? "Replace Banner Image" : "Select Banner Image"}
								</Button>
								{bannerImageUrl && (
									<Button
										variant="link"
										isDestructive
										onClick={() => setAttributes({ bannerImageId: 0, bannerImageUrl: "" })}
										style={{ marginTop: 4 }}
									>
										Remove
									</Button>
								)}
							</div>
						)}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="ap__container">
					<div className="ap__grid">
						{/* Column Left */}
						<div className="ap__col ap__col--left">
							<div className="ap__subheading-wrap">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ transform: "rotate(45deg)", marginRight: 8 }}>
									<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
								</svg>
								<RichText
									tagName="h6"
									className="ap__subheading"
									value={subheading}
									onChange={(val) => setAttributes({ subheading: val })}
									placeholder="Category..."
								/>
							</div>
							<RichText
								tagName="h2"
								className="ap__heading"
								value={heading}
								onChange={(val) => setAttributes({ heading: val })}
								placeholder="Heading..."
							/>
						</div>

						{/* Column Right */}
						<div className="ap__col ap__col--right">
							<RichText
								tagName="p"
								className="ap__desc"
								value={description}
								onChange={(val) => setAttributes({ description: val })}
								placeholder="Description..."
							/>
							<div className="ap__btn-wrap">
								<span className="ap__btn">
									{buttonText || "LEARN MORE"}
									<span className="ap__btn-circle">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"/>
										</svg>
									</span>
								</span>
							</div>
						</div>
					</div>

					{/* Banner Image Placeholder/Preview */}
					<div className="ap__banner">
						{bannerImageUrl ? (
							<img className="ap__banner-img" src={bannerImageUrl} alt="Banner Space" />
						) : (
							<MediaUpload
								onSelect={(media) => setAttributes({ bannerImageId: media.id, bannerImageUrl: media.url })}
								allowedTypes={["image"]}
								render={({ open }) => (
									<div className="ap__banner-placeholder" onClick={open} role="button" tabIndex={0}>
										<span>+ Select space banner image</span>
									</div>
								)}
							/>
						)}
					</div>
				</div>
			</div>
		</>
	);
}

import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, TextControl, RangeControl, ColorPalette, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { tickerText, speed, textColor, bgColor, logoId, logoUrl, logoHeight } = attributes;
	const blockProps = useBlockProps({ className: "st" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Ticker Settings" initialOpen={true}>
					<RangeControl
						label="Scroll Speed (Seconds)"
						value={speed}
						onChange={(val) => setAttributes({ speed: val })}
						min={2}
						max={40}
					/>
					<RangeControl
						label="Logo Height (px)"
						value={logoHeight}
						onChange={(val) => setAttributes({ logoHeight: val })}
						min={20}
						max={150}
					/>
				</PanelBody>
				<PanelBody title="Logo Image" initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ logoId: media.id, logoUrl: media.url })}
							allowedTypes={["image"]}
							value={logoId}
							render={({ open }) => (
								<Button onClick={open} variant="primary">
									{logoUrl ? "Change Logo" : "Select Logo"}
								</Button>
							)}
						/>
						{logoUrl && (
							<Button onClick={() => setAttributes({ logoId: 0, logoUrl: "" })} variant="secondary" style={{ marginLeft: "10px" }}>
								Remove
							</Button>
						)}
					</MediaUploadCheck>
				</PanelBody>
				<PanelBody title="Colors" initialOpen={true}>
					<p>Background Color</p>
					<ColorPalette
						value={bgColor}
						onChange={(val) => setAttributes({ bgColor: val })}
					/>
					{!logoUrl && (
						<>
							<p>Text Color</p>
							<ColorPalette
								value={textColor}
								onChange={(val) => setAttributes({ textColor: val })}
							/>
						</>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps} style={{ backgroundColor: bgColor, color: textColor }}>
				<div className="st__wrap">
					<div className="st__track" style={{ animationDuration: `${speed}s` }}>
						<div className="st__content">
							{logoUrl ? (
								<>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
								</>
							) : (
								<>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
								</>
							)}
						</div>
						<div className="st__content">
							{logoUrl ? (
								<>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
									<img className="st__logo" src={logoUrl} style={{ height: `${logoHeight}px`, width: "auto" }} alt="Logo" />
									<span className="st__dot"></span>
								</>
							) : (
								<>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
									<span className="st__text">{tickerText}</span>
									<span className="st__dot"></span>
								</>
							)}
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

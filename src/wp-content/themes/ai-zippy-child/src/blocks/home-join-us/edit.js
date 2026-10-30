import { useBlockProps, RichText, InspectorControls, PanelColorSettings } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, btnText, btnUrl, textAlign, backgroundColor } = attributes;
	const blockProps = useBlockProps({ 
		className: `hju has-text-align-${textAlign || "center"}`,
		style: { backgroundColor: backgroundColor || "#0b0b0b" }
	});

	return (
		<>
			<InspectorControls>
				<PanelColorSettings
					title="Background Settings"
					initialOpen={true}
					colorSettings={[
						{
							value: backgroundColor || "#0b0b0b",
							onChange: (val) => setAttributes({ backgroundColor: val }),
							label: "Background Color",
						},
					]}
				/>
				<PanelBody title="Button Settings" initialOpen={true}>
					<TextControl
						label="Button Link URL"
						value={btnUrl}
						onChange={(val) => setAttributes({ btnUrl: val })}
						placeholder="https://example.com/join-us"
					/>
					<TextControl
						label="Button Text"
						value={btnText}
						onChange={(val) => setAttributes({ btnText: val })}
					/>
					<SelectControl
						label="Text Alignment"
						value={textAlign || "center"}
						options={[
							{ label: "Left", value: "left" },
							{ label: "Center", value: "center" },
							{ label: "Right", value: "right" },
							{ label: "Justify", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="hju__container">
					<RichText
						tagName="h2"
						className="hju__title"
						value={title}
						onChange={(val) => setAttributes({ title: val })}
						placeholder="JOIN US"
					/>
					<div className="hju__btn-wrap">
						<span className="hju__btn-mock">
							{btnText || "GET STARTED WITH WELIVE"}
						</span>
					</div>
				</div>
			</div>
		</>
	);
}

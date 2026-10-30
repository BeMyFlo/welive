import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextareaControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, mobileBackgroundText, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `abh has-text-align-${textAlign || "left"}` });

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
				<PanelBody title="Hero Text Settings" initialOpen={true}>
					<TextareaControl
						label="Right Stacked Big Text"
						value={mobileBackgroundText}
						onChange={(val) => setAttributes({ mobileBackgroundText: val })}
						help="Use Enter to type each word on a new line."
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="abh__container">
					<div className="abh__flex">
						<div className="abh__left">
							<RichText
								tagName="h1"
								className="abh__title"
								value={title}
								onChange={(val) => setAttributes({ title: val })}
								placeholder="Hero Title..."
							/>
							<RichText
								tagName="p"
								className="abh__subtitle"
								value={subtitle}
								onChange={(val) => setAttributes({ subtitle: val })}
								placeholder="Hero Description..."
							/>
						</div>
						
						<div className="abh__right">
							<RichText
								tagName="h2"
								className="abh__big-text"
								value={mobileBackgroundText}
								onChange={(val) => setAttributes({ mobileBackgroundText: val })}
								placeholder="Big Text..."
							/>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

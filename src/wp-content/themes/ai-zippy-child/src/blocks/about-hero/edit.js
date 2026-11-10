import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextareaControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, mobileBackgroundText, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `abh has-text-align-${textAlign || "left"}` });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Căn chữ" initialOpen={false}>
					<SelectControl
						label="Căn chữ"
						value={textAlign || "left"}
						options={[
							{ label: "Trái", value: "left" },
							{ label: "Giữa", value: "center" },
							{ label: "Phải", value: "right" },
							{ label: "Đều hai bên", value: "justify" }
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

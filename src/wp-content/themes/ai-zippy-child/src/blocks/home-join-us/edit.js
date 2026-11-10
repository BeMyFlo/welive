import { useBlockProps, RichText, InspectorControls, PanelColorSettings } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, btnText, btnUrl, textAlign, backgroundColor, variant, kicker } = attributes;
	const blockProps = useBlockProps({ 
		className: `hju hju--${variant || "classic"} has-text-align-${textAlign || "center"}`,
		style: { backgroundColor: backgroundColor || "#0b0b0b" }
	});

	return (
		<>
			<InspectorControls>
				<PanelColorSettings
					title="Màu nền"
					initialOpen={true}
					colorSettings={[
						{
							value: backgroundColor || "#0b0b0b",
							onChange: (val) => setAttributes({ backgroundColor: val }),
							label: "Màu nền",
						},
					]}
				/>
				<PanelBody title="Nút" initialOpen={true}>
					<TextControl
						label="Link của nút"
						value={btnUrl}
						onChange={(val) => setAttributes({ btnUrl: val })}
						placeholder="https://example.com/join-us"
					/>
					<TextControl
						label="Chữ trên nút"
						value={btnText}
						onChange={(val) => setAttributes({ btnText: val })}
					/>
					<SelectControl
						label="Kiểu"
						value={variant || "classic"}
						options={[
							{ label: "Classic (giữa, nền đen)", value: "classic" },
							{ label: "Band (tiêu đề trái, nút phải)", value: "band" },
						]}
						onChange={(val) => setAttributes({ variant: val })}
					/>
					<SelectControl
						label="Căn chữ"
						value={textAlign || "center"}
						options={[
							{ label: "Trái", value: "left" },
							{ label: "Giữa", value: "center" },
							{ label: "Phải", value: "right" },
							{ label: "Đều hai bên", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="hju__container">
					<div className="hju__heading">
						{variant === "band" && (
							<RichText
								tagName="p"
								className="hju__kicker"
								value={kicker}
								onChange={(val) => setAttributes({ kicker: val })}
								placeholder="Nhãn nhỏ"
								allowedFormats={[]}
							/>
						)}
						<RichText
							tagName="h2"
							className="hju__title"
							value={title}
							onChange={(val) => setAttributes({ title: val })}
							placeholder="JOIN US"
						/>
					</div>
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

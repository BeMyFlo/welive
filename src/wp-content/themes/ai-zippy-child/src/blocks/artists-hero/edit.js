import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
	const { title, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `ah has-text-align-${textAlign || "left"}` });

	return (
		<div {...blockProps}>
			<div className="ah__container">
				<RichText
					tagName="h1"
					className="ah__title"
					value={title}
					onChange={(val) => setAttributes({ title: val })}
					placeholder="Enter Artists Page Title..."
					allowedFormats={[]}
				/>
			</div>
		</div>
	);
}

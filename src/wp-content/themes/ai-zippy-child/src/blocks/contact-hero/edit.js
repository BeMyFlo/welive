import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
	const { title, textAlign } = attributes;
	const blockProps = useBlockProps({ className: `ch has-text-align-${textAlign || "left"}` });

	return (
		<div {...blockProps}>
			<div className="ch__container">
				<RichText
					tagName="h1"
					className="ch__title"
					value={title}
					onChange={(val) => setAttributes({ title: val })}
					placeholder="Enter Contact Page Title..."
					allowedFormats={[]}
				/>
			</div>
		</div>
	);
}

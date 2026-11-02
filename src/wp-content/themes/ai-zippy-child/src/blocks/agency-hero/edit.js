import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, description, images } = attributes;
	const blockProps = useBlockProps({ className: "agh" });
	const updateImage = (index, patch) => setAttributes({ images: images.map((img, i) => (i === index ? { ...img, ...patch } : img)) });

	return (
		<>
			<InspectorControls />
			<div {...blockProps}>
				<div className="agh__container">
					<div className="agh__text">
						<RichText tagName="span" className="agh__eyebrow" value={eyebrow} onChange={(val) => setAttributes({ eyebrow: val })} placeholder="Eyebrow..." allowedFormats={[]} />
						<RichText tagName="h1" className="agh__title" value={title} onChange={(val) => setAttributes({ title: val })} placeholder="Title..." />
						<RichText tagName="p" className="agh__desc" value={description} onChange={(val) => setAttributes({ description: val })} placeholder="Description..." />
					</div>
					<div className="agh__collage">
						{images.slice(0, 4).map((img, i) => (
							<ImagePicker key={i} className={`agh__img agh__img--${i + 1}`} imageId={img.imageId} imageUrl={img.imageUrl} onSelect={(patch) => updateImage(i, patch)} />
						))}
					</div>
				</div>
			</div>
		</>
	);
}

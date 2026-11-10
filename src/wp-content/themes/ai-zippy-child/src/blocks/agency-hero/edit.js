import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, description, images, layout, titleAccent, image } = attributes;
	const isSplit = layout === "split";
	const blockProps = useBlockProps({ className: isSplit ? "agh agh--split" : "agh" });
	const updateImage = (index, patch) => setAttributes({ images: images.map((img, i) => (i === index ? { ...img, ...patch } : img)) });
	const photo = image || {};
	const updatePhoto = (patch) => setAttributes({ image: { ...photo, ...patch } });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Kiểu hiển thị" initialOpen={true}>
					<SelectControl
						label="Kiểu hiển thị"
						value={isSplit ? "split" : "collage"}
						options={[
							{ label: "Ảnh lớn bên phải (demo mới)", value: "split" },
							{ label: "Bố cục 4 ảnh (cũ)", value: "collage" },
						]}
						onChange={(v) => setAttributes({ layout: v })}
					/>
					{isSplit && (
						<>
							<TextControl label="Alt ảnh" value={photo.alt || ""} onChange={(v) => updatePhoto({ alt: v })} />
							<TextControl label="Vị trí ảnh (object-position, vd 50% 55%)" value={photo.position || ""} onChange={(v) => updatePhoto({ position: v })} />
						</>
					)}
				</PanelBody>
			</InspectorControls>
			{isSplit ? (
				<section {...blockProps}>
					<div className="agh__s-wrap">
						<div className="agh__s-copy">
							<RichText tagName="p" className="agh__s-eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
							<RichText tagName="h1" className="agh__s-title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
							<RichText tagName="p" className="agh__s-accent" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Tiêu đề (dòng màu cam)" allowedFormats={[]} />
							<RichText tagName="p" className="agh__s-desc" value={description} onChange={(v) => setAttributes({ description: v })} placeholder="Mô tả" />
						</div>
						<ImagePicker className="agh__s-photo-edit" imageId={photo.imageId} imageUrl={photo.imageUrl} onSelect={updatePhoto} />
					</div>
				</section>
			) : (
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
			)}
		</>
	);
}

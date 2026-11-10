import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { image, title, titleAccent, btnText, btnUrl } = attributes;
	const blockProps = useBlockProps({ className: "ctb" });
	const photo = image || {};
	const updatePhoto = (patch) => setAttributes({ image: { ...photo, ...patch } });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Ảnh nền" initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(m) => updatePhoto({ imageId: m.id, imageUrl: m.url })}
							allowedTypes={["image"]}
							value={photo.imageId}
							render={({ open }) => (
								<Button variant="secondary" onClick={open} style={{ marginBottom: 12 }}>
									{photo.imageUrl ? "Đổi ảnh nền" : "Chọn ảnh nền"}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					<TextControl label="Alt ảnh" value={photo.alt || ""} onChange={(v) => updatePhoto({ alt: v })} />
					<TextControl label="Vị trí ảnh (object-position, vd 54% 48%)" value={photo.position || ""} onChange={(v) => updatePhoto({ position: v })} />
				</PanelBody>
				<PanelBody title="Nút & anchor" initialOpen={true}>
					<TextControl label="Anchor (id để link tới)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Link nút" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				{photo.imageUrl && <img className="ctb__image" src={photo.imageUrl} alt="" style={{ objectPosition: photo.position || "54% 48%" }} />}
				<div className="ctb__wrap">
					<div className="ctb__heading">
						<RichText tagName="h1" className="ctb__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" className="ctb__accent" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Chữ màu cam" allowedFormats={[]} />
					</div>
					<span className="ctb__btn">
						<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Chữ nút" allowedFormats={[]} />
					</span>
				</div>
			</section>
		</>
	);
}

import { MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";

// Clickable image slot for block canvases. onSelect receives { imageId, imageUrl }.
export default function ImagePicker({ imageId, imageUrl, onSelect, className, label = "+ Ảnh" }) {
	return (
		<MediaUploadCheck>
			<MediaUpload
				onSelect={(media) => onSelect({ imageId: media.id, imageUrl: media.url })}
				allowedTypes={["image"]}
				value={imageId}
				render={({ open }) => (
					<div className={className} onClick={open} role="button" tabIndex={0}>
						{imageUrl ? <img src={imageUrl} alt="" /> : <span className="az-img-empty">{label}</span>}
					</div>
				)}
			/>
		</MediaUploadCheck>
	);
}

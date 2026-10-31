import { RichText, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { Button } from "@wordpress/components";

// Editable list of { imageId, imageUrl, text } cards ("frames") with an add-frame slot.
// `prefix` is the BEM block prefix of the calling block (e.g. "sg", "abvm").
export default function CardsEditor({ prefix, cards = [], onChange }) {
	const update = (index, patch) => onChange(cards.map((card, i) => (i === index ? { ...card, ...patch } : card)));
	const remove = (index) => onChange(cards.filter((_, i) => i !== index));
	const add = () => onChange([...cards, { imageId: 0, imageUrl: "", text: "" }]);

	return (
		<div className={`${prefix}__cards`}>
			{cards.map((card, index) => (
				<figure className={`${prefix}__card`} key={index}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => update(index, { imageId: media.id, imageUrl: media.url })}
							allowedTypes={["image"]}
							value={card.imageId}
							render={({ open }) => (
								<div className={`${prefix}__card-media`} onClick={open} role="button" tabIndex={0}>
									{card.imageUrl ? <img src={card.imageUrl} alt="" /> : <span className={`${prefix}__card-placeholder`}>+ Ảnh</span>}
								</div>
							)}
						/>
					</MediaUploadCheck>
					<RichText
						tagName="figcaption"
						className={`${prefix}__card-text`}
						value={card.text}
						onChange={(val) => update(index, { text: val })}
						placeholder="Chữ sửa được"
					/>
					<Button className={`${prefix}__card-remove`} variant="secondary" isDestructive isSmall onClick={() => remove(index)}>
						Xoá khung
					</Button>
				</figure>
			))}
			<button type="button" className={`${prefix}__card-add`} onClick={add}>
				+ khung
			</button>
		</div>
	);
}

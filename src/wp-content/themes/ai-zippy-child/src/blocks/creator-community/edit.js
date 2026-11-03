import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";
import BulletsEditor from "../shared/BulletsEditor.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleAccent, bullets, cardLabel, cardKicker, cardTitle, cardTitle2, logoUrl, logoId, photos, avatars, avatarText, btnText, btnUrl } = attributes;
	const blockProps = useBlockProps({ className: "ccm" });
	const set = (key) => (val) => setAttributes({ [key]: val });
	const updateList = (key, list, index, patch) => setAttributes({ [key]: list.map((x, i) => (i === index ? { ...x, ...patch } : x)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Button" initialOpen={true}>
					<TextControl label="Button link" value={btnUrl} onChange={set("btnUrl")} help='Dùng "/duong-dan/" cho trang trong site.' />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="ccm__container">
					<div className="ccm__card">
						<div className="ccm__card-top">
							<ImagePicker className="ccm__logo-picker" imageId={logoId} imageUrl={logoUrl} label="+ Logo (mặc định: logo site)" onSelect={({ imageId, imageUrl }) => setAttributes({ logoId: imageId, logoUrl: imageUrl })} />
							<RichText tagName="span" className="ccm__card-label" value={cardLabel} onChange={set("cardLabel")} placeholder="Label" allowedFormats={[]} />
						</div>
						<div className="ccm__photos">
							{photos.slice(0, 3).map((photo, i) => (
								<ImagePicker key={i} className={`ccm__photo ccm__photo--${i + 1}`} imageId={photo.imageId} imageUrl={photo.imageUrl} onSelect={(patch) => updateList("photos", photos, i, patch)} />
							))}
						</div>
						<div className="ccm__card-text">
							<RichText tagName="span" className="ccm__kicker" value={cardKicker} onChange={set("cardKicker")} placeholder="Kicker" allowedFormats={[]} />
							<RichText tagName="strong" className="ccm__card-title" value={cardTitle} onChange={set("cardTitle")} placeholder="Title line 1" allowedFormats={[]} />
							<RichText tagName="strong" className="ccm__card-title" value={cardTitle2} onChange={set("cardTitle2")} placeholder="Title line 2" allowedFormats={[]} />
						</div>
					</div>

					<div className="ccm__content">
						<RichText tagName="span" className="ccm__eyebrow" value={eyebrow} onChange={set("eyebrow")} placeholder="Eyebrow..." allowedFormats={[]} />
						<h2 className="ccm__title">
							<RichText tagName="span" value={title} onChange={set("title")} placeholder="Title..." allowedFormats={[]} />{" "}
							<RichText tagName="span" className="ccm__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Accent..." allowedFormats={[]} />
						</h2>
						<BulletsEditor items={bullets} onChange={set("bullets")} listClass="ccm__list" />
						<div className="ccm__actions">
							<div className="ccm__people">
								<span className="ccm__avatars">
									{avatars.map((a, i) => (
										<ImagePicker key={i} className="ccm__avatar" imageId={a.imageId} imageUrl={a.imageUrl} label="+" onSelect={(patch) => updateList("avatars", avatars, i, patch)} />
									))}
								</span>
								<RichText tagName="span" className="ccm__people-text" value={avatarText} onChange={set("avatarText")} placeholder="Text" allowedFormats={[]} />
							</div>
							<span className="ccm__btn"><RichText tagName="span" value={btnText} onChange={set("btnText")} placeholder="Button..." allowedFormats={[]} /></span>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

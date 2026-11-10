import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl } from "@wordpress/components";
import ItemsPanel from "../shared/ItemsPanel.js";
import { dec, enc } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const a = attributes;
	const blockProps = useBlockProps({ className: "ccm" });
	const set = (key) => (v) => setAttributes({ [key]: v });
	const bullets = a.bullets || [];
	const photos = a.photos || [];
	const avatars = a.avatars || [];
	const show = (list, n) => list.slice(0, n);

	return (
		<>
			<InspectorControls>
				<PanelBody title="Các ý bên phải" initialOpen={true}>
					<TextareaControl label="Các ý (mỗi dòng một ý)" rows={6} value={bullets.map(dec).join("\n")} onChange={(v) => setAttributes({ bullets: v.split("\n").map(enc) })} />
				</PanelBody>
				<ItemsPanel
					title="Ảnh trong thẻ"
					hint="Tối đa 3 ảnh chân dung xếp chồng trong thẻ tối."
					items={photos}
					setItems={(v) => setAttributes({ photos: v })}
					itemLabel={() => "Ảnh"}
					makeItem={() => ({ imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm ảnh"
					max={3}
					fields={[{ key: "imageId", type: "image", label: "Ảnh" }]}
					initialOpen={false}
				/>
				<ItemsPanel
					title="Ảnh đại diện nhỏ"
					hint="Các vòng tròn nhỏ cạnh nút."
					items={avatars}
					setItems={(v) => setAttributes({ avatars: v })}
					itemLabel={() => "Ảnh"}
					makeItem={() => ({ imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm ảnh"
					max={4}
					fields={[{ key: "imageId", type: "image", label: "Ảnh" }]}
					initialOpen={false}
				/>
				<PanelBody title="Nút & logo" initialOpen={false}>
					<TextControl label="Link nút" value={a.btnUrl} onChange={set("btnUrl")} help='Dùng "/duong-dan/" cho trang trong site.' />
					<TextControl label="Link logo (để trống = logo của site)" value={a.logoUrl || ""} onChange={set("logoUrl")} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="ccm__container">
					<div className="ccm__card">
						<div className="ccm__card-top">
							{a.logoUrl ? <img className="ccm__logo" src={a.logoUrl} alt="" /> : null}
							<RichText tagName="span" className="ccm__card-label" value={a.cardLabel} onChange={set("cardLabel")} placeholder="Nhãn trên thẻ" allowedFormats={[]} />
						</div>
						<div className="ccm__photos">
							{show(photos, 3).map((p, i) => (
								<div key={i} className={`ccm__photo ccm__photo--${i + 1}`}>
									{p.imageUrl ? <img src={p.imageUrl} alt="" /> : <span className="hm-img-empty">Ảnh</span>}
								</div>
							))}
						</div>
						<div className="ccm__card-text">
							<RichText tagName="span" className="ccm__kicker" value={a.cardKicker} onChange={set("cardKicker")} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<RichText tagName="strong" className="ccm__card-title" value={a.cardTitle} onChange={set("cardTitle")} placeholder="Tiêu đề thẻ dòng 1" allowedFormats={[]} />
							<RichText tagName="strong" className="ccm__card-title" value={a.cardTitle2} onChange={set("cardTitle2")} placeholder="Tiêu đề thẻ dòng 2" allowedFormats={[]} />
						</div>
					</div>
					<div className="ccm__content">
						<RichText tagName="span" className="ccm__eyebrow" value={a.eyebrow} onChange={set("eyebrow")} placeholder="Nhãn nhỏ" allowedFormats={[]} />
						<RichText tagName="h2" className="ccm__title" value={a.title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" className="ccm__title ccm__title-accent" value={a.titleAccent} onChange={set("titleAccent")} placeholder="Tiêu đề (chữ màu)" allowedFormats={[]} />
						<ul className="ccm__list">
							{bullets.filter((b) => b.trim() !== "").map((b, i) => (
								<li key={i} dangerouslySetInnerHTML={{ __html: b }} />
							))}
						</ul>
						<div className="ccm__actions">
							<div className="ccm__people">
								<span className="ccm__avatars">
									{avatars.map((v, i) => (
										<span key={i} className="ccm__avatar">{v.imageUrl ? <img src={v.imageUrl} alt="" /> : null}</span>
									))}
								</span>
								<RichText tagName="span" className="ccm__people-text" value={a.avatarText} onChange={set("avatarText")} placeholder="Chữ cạnh ảnh đại diện" allowedFormats={[]} />
							</div>
							<span className="ccm__btn">
								<RichText tagName="span" value={a.btnText} onChange={set("btnText")} placeholder="Chữ nút" allowedFormats={[]} />
							</span>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

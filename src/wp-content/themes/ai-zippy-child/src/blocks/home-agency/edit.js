import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { kicker, title, titleAccent, linkText, linkUrl, cards = [] } = attributes;
	const blockProps = useBlockProps({ className: "hag" });
	const setCard = (i, patch) => setAttributes({ cards: cards.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Link mặc định" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #agency)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<SelectControl
						label="Kiểu nút cạnh tiêu đề"
						value={attributes.linkStyle || "link"}
						options={[
							{ label: "Pill nâu có icon (mới)", value: "tag" },
							{ label: "Link chữ + mũi tên (cũ)", value: "link" },
						]}
						onChange={(v) => setAttributes({ linkStyle: v })}
					/>
					<SelectControl
						label="Icon trong pill"
						value={attributes.linkIcon || "none"}
						options={[
							{ label: "Không", value: "none" },
							{ label: "Vương miện", value: "crown" },
							{ label: "Cặp tài liệu", value: "briefcase" },
						]}
						onChange={(v) => setAttributes({ linkIcon: v })}
					/>
					<TextControl label="Link 'Khám phá giải pháp' (và card nếu không có link riêng)" value={linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
				</PanelBody>
				<ItemsPanel
					title="Thẻ ảnh lớn"
					hint="Mỗi thẻ có ảnh nền, nhãn, tiêu đề và mô tả. Canvas là bản xem trước."
					items={cards}
					setItems={(v) => setAttributes({ cards: v })}
					itemLabel={(c) => dec(String(c.title || "").replace(/<br\s*\/?>/gi, " "))}
					makeItem={() => ({ label: "NHÃN", title: "Tiêu đề", text: "", url: "", imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm thẻ"
					fields={[
						{ key: "imageId", type: "image", label: "Ảnh nền" },
						{ key: "label", label: "Nhãn nhỏ phía trên" },
						{ key: "title", label: "Tiêu đề", type: "multiline" },
						{ key: "text", label: "Mô tả", type: "textarea", rows: 3 },
						{ key: "url", label: "Link riêng (để trống = link mặc định ở trên)", type: "url" },
					]}
				/>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hag__wrap">
					<div className="hag__top">
						<div>
							<RichText tagName="p" className="hag__kicker" value={kicker} onChange={(v) => setAttributes({ kicker: v })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<h2 className="hag__title">
								<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Giải pháp" />{" "}
								<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Agency." />
							</h2>
						</div>
						<span className="hag__link-mock">
							<RichText tagName="span" value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Link" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<div className="hag__grid">
						{cards.map((c, i) => (
							<div className="hag__card" key={i}>
								{c.imageUrl ? <img src={c.imageUrl} alt="" /> : <span className="hm-img-empty">Chưa có ảnh</span>}
								<div>
									<Html tag="p" value={c.label} />
									<Html tag="h3" value={c.title} />
									<Html value={c.text} />
								</div>
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}

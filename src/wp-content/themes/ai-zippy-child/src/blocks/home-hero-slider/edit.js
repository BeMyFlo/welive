import { useState } from "@wordpress/element";
import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, slides = [], textAlign, ctaText, ctaUrl, kicker } = attributes;
	const [active, setActive] = useState(0);
	const blockProps = useBlockProps({ className: `hhs hhs--edit has-text-align-${textAlign || "left"}` });
	const current = Math.min(active, Math.max(slides.length - 1, 0));
	const cur = slides[current] || {};

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Các ảnh trượt"
					hint="Mỗi mục là một ảnh chân dung kèm tên lớn. Bấm ảnh nhỏ trên canvas để xem trước từng ảnh."
					items={slides}
					setItems={(v) => setAttributes({ slides: v })}
					active={current}
					setActive={setActive}
					itemLabel={(s) => dec(s.artistName) || dec(s.title)}
					makeItem={() => ({ imageId: 0, imageUrl: "", artistName: "Nhà sáng tạo mới", position: "50% 40%", title: "TÊN LỚN", subtitle: "" })}
					addLabel="+ Thêm ảnh"
					fields={[
						{ key: "imageId", type: "image", label: "Ảnh chân dung" },
						{ key: "title", label: "Tên lớn trên ảnh" },
						{ key: "subtitle", label: "Dòng mô tả nhỏ (tuỳ chọn)", type: "textarea", rows: 3 },
						{ key: "artistName", label: "Tên gọi nội bộ (chỉ để nhận biết trong danh sách)" },
						{ key: "position", label: "Vị trí cắt ảnh (vd 50% 40%)", type: "url", help: "Chỉnh khi mặt bị cắt mất: giá trị đầu là ngang, giá trị sau là dọc." },
					]}
				/>
				<PanelBody title="Nút chính" initialOpen={false}>
					<TextControl label="Link của nút" value={ctaUrl} onChange={(v) => setAttributes({ ctaUrl: v })} help='Dùng "/duong-dan/" cho trang trong site.' />
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hhs__pictures">
					<figure className="hhs__panel is-active">{cur.imageUrl ? <img className="hhs__slide-img" src={cur.imageUrl} alt="" style={{ objectPosition: cur.position || "50% 40%" }} /> : <span className="hm-img-empty">Chưa có ảnh — chọn ở thanh bên phải</span>}</figure>
				</div>
				<div className="hhs__content">
					<div className="hhs__content-left">
						<RichText tagName="p" className="hhs__kicker" value={kicker} onChange={(v) => setAttributes({ kicker: v })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
						<Html tag="h1" className="hhs__title" value={cur.title !== undefined ? cur.title : title} />
						<Html tag="p" className="hhs__subtitle" value={cur.subtitle !== undefined ? cur.subtitle : subtitle} />
					</div>
					<span className="hhs__cta">
						<RichText tagName="span" className="hhs__cta-label" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} placeholder="Chữ nút" allowedFormats={[]} />
					</span>
				</div>
				<div className="hhs__thumbs">
					{slides.map((s, i) => (
						<button key={i} type="button" className={`hhs__thumb${i === current ? " is-active" : ""}`} onClick={() => setActive(i)} aria-label={dec(s.artistName)}>
							{s.imageUrl ? <img src={s.imageUrl} alt="" /> : <span>{i + 1}</span>}
						</button>
					))}
				</div>
			</section>
		</>
	);
}

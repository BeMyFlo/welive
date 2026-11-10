import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, RangeControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { badge, title, titleAccent, items = [], placeholderCount = 0, placeholderTitle, placeholderText, footnote } = attributes;
	const blockProps = useBlockProps({ className: "cwl" });
	const set = (key) => (v) => setAttributes({ [key]: v });

	return (
		<>
			<InspectorControls>
				<ItemsPanel
					title="Nhà sáng tạo"
					hint="Thêm, xoá, đổi thứ tự và chọn ảnh cho từng người. Canvas là bản xem trước."
					items={items}
					setItems={(v) => setAttributes({ items: v })}
					itemLabel={(it) => dec(it.name)}
					makeItem={() => ({ name: "TÊN", linkText: "Xem câu chuyện", linkUrl: "", imageId: 0, imageUrl: "" })}
					addLabel="+ Thêm nhà sáng tạo"
					fields={[
						{ key: "name", label: "Tên" },
						{ key: "linkText", label: "Chữ link nhỏ" },
						{ key: "linkUrl", label: "Link (để trống = không bấm được)", type: "url", help: 'Dùng "/duong-dan/" cho trang trong site.' },
						{ key: "imageId", type: "image", label: "Ảnh" },
					]}
				/>
				<PanelBody title="Thẻ 'Đang cập nhật'" initialOpen={false}>
					<RangeControl label="Số thẻ chờ" value={placeholderCount} min={0} max={6} onChange={(v) => setAttributes({ placeholderCount: v })} />
					<TextControl label="Tiêu đề thẻ chờ" value={placeholderTitle || ""} onChange={set("placeholderTitle")} />
					<TextControl label="Chữ thẻ chờ" value={placeholderText || ""} onChange={set("placeholderText")} />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="cwl__container">
					<div className="cwl__header">
						<div className="cwl__intro">
							<RichText tagName="span" className="cwl__badge" value={badge} onChange={set("badge")} placeholder="Nhãn" allowedFormats={[]} />
							<RichText tagName="h2" className="cwl__title" value={title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
							<RichText tagName="p" className="cwl__title cwl__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Tiêu đề (dòng màu)" allowedFormats={[]} />
						</div>
					</div>
					<div className="cwl__track">
						{items.map((it, i) => (
							<div key={i} className="cwl__card">
								{it.imageUrl ? <img className="cwl__card-img" src={it.imageUrl} alt="" /> : <span className="hm-img-empty">Chưa có ảnh</span>}
								<div className="cwl__card-info">
									<Html tag="strong" className="cwl__card-name" value={it.name} />
									<Html className="cwl__card-link" value={it.linkText} />
								</div>
							</div>
						))}
						{Array.from({ length: placeholderCount }).map((_, i) => (
							<div key={`p${i}`} className="cwl__card cwl__card--placeholder">
								<span className="cwl__mark">W.</span>
								<div className="cwl__card-info">
									<strong className="cwl__card-name">{placeholderTitle}</strong>
									<span className="cwl__card-link">{placeholderText}</span>
								</div>
							</div>
						))}
					</div>
					<RichText tagName="p" className="cwl__footnote" value={footnote} onChange={set("footnote")} placeholder="Ghi chú cuối" allowedFormats={[]} />
				</div>
			</div>
		</>
	);
}

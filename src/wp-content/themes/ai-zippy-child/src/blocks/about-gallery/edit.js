import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, SelectControl, RangeControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, titleAccent, intro, allLabel, moreText, unit, perPage, filters = [], items = [] } = attributes;
	const blockProps = useBlockProps({ className: "agl" });

	const setItem = (i, patch) => setAttributes({ items: items.map((it, k) => (k === i ? { ...it, ...patch } : it)) });
	const setFilter = (i, patch) => setAttributes({ filters: filters.map((f, k) => (k === i ? { ...f, ...patch } : f)) });
	const categoryOptions = [{ label: "(không có)", value: "" }, ...filters.map((f) => ({ label: f.label, value: f.key }))];

	return (
		<>
			<InspectorControls>
				<PanelBody title="Hiển thị" initialOpen={true}>
					<RangeControl label="Số ảnh hiển thị ban đầu" value={perPage} min={3} max={12} onChange={(v) => setAttributes({ perPage: v })} />
					<TextControl label="Nhãn nút 'Tất cả'" value={allLabel} onChange={(v) => setAttributes({ allLabel: v })} />
					<TextControl label="Đơn vị đếm (vd: khoảnh khắc)" value={unit} onChange={(v) => setAttributes({ unit: v })} />
					<TextControl label="Chữ nút 'Xem thêm'" value={moreText} onChange={(v) => setAttributes({ moreText: v })} />
				</PanelBody>
				<PanelBody title="Bộ lọc" initialOpen={false}>
					{filters.map((f, i) => (
						<div key={i} style={{ marginBottom: 12 }}>
							<TextControl label="Tên hiển thị" value={f.label} onChange={(v) => setFilter(i, { label: v })} />
							<TextControl label="Mã (không dấu, vd: tiktok)" value={f.key} onChange={(v) => setFilter(i, { key: v.toLowerCase().replace(/[^a-z0-9-]/g, "") })} />
						</div>
					))}
					<Button variant="secondary" onClick={() => setAttributes({ filters: [...filters, { key: "", label: "" }] })}>
						+ Thêm bộ lọc
					</Button>
					{filters.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ filters: filters.slice(0, -1) })}>
							Xoá bộ lọc cuối
						</Button>
					)}
				</PanelBody>
				<PanelBody title="Chi tiết từng ảnh" initialOpen={false}>
					{items.map((it, i) => (
						<div key={i} style={{ marginBottom: 16, paddingBottom: 12, borderBottom: "1px solid #ddd" }}>
							<strong>#{i + 1} {it.title}</strong>
							<SelectControl label="Nhóm" value={it.category || ""} options={categoryOptions} onChange={(v) => setItem(i, { category: v })} />
							<TextareaControl label="Mô tả (hiện trong ảnh phóng to)" value={it.description || ""} onChange={(v) => setItem(i, { description: v })} />
						</div>
					))}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="agl__wrap">
					<div className="agl__heading">
						<div>
							<RichText tagName="p" className="agl__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
							<h2 className="agl__title">
								<RichText tagName="span" style={{ display: "block", color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
								<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Tiêu đề phụ" allowedFormats={[]} />
							</h2>
						</div>
						<div className="agl__aside">
							<RichText tagName="p" value={intro} onChange={(v) => setAttributes({ intro: v })} placeholder="Mô tả" />
							<div className="agl__filters">
								<span className="agl__filter-pill">{allLabel}</span>
								{filters.map((f, i) => (
									<span className="agl__filter-pill" key={i}>{f.label}</span>
								))}
							</div>
						</div>
					</div>
					<div className="agl__grid">
						{items.map((it, i) => (
							<div className="agl__card agl__item-edit" key={i}>
								<div className="agl__image">
									<ImagePicker imageId={it.imageId} imageUrl={it.imageUrl} onSelect={(v) => setItem(i, v)} />
								</div>
								<div className="agl__caption">
									<RichText tagName="span" className="agl__name" value={it.title} onChange={(v) => setItem(i, { title: v })} placeholder="Tiêu đề ảnh" allowedFormats={[]} />
									<RichText tagName="small" value={it.label} onChange={(v) => setItem(i, { label: v })} placeholder="NHÃN" allowedFormats={[]} />
								</div>
								<Button className="agl__item-remove" isDestructive variant="link" onClick={() => setAttributes({ items: items.filter((_, k) => k !== i) })}>
									Xoá ảnh
								</Button>
							</div>
						))}
					</div>
					<Button variant="secondary" style={{ marginTop: 20 }} onClick={() => setAttributes({ items: [...items, { title: "", label: "", category: "", description: "", imageId: 0, imageUrl: "" }] })}>
						+ Thêm ảnh
					</Button>
				</div>
			</section>
		</>
	);
}

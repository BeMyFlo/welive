import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, ToggleControl, RangeControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, rowTitle, items = [], pendingCount, pendingTitle, pendingText, prevText, nextText, previewNote, dialogFallback, isLast } = attributes;
	const blockProps = useBlockProps({ className: "cst" });
	const setItem = (i, patch) => setAttributes({ items: items.map((it, k) => (k === i ? { ...it, ...patch } : it)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Hàng này" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #cau-chuyen)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Nhãn nhỏ phía trên (để trống nếu là hàng thứ hai)" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} />
					<ToggleControl label="Là hàng cuối (thêm khoảng cách dưới)" checked={!!isLast} onChange={(v) => setAttributes({ isLast: v })} />
					<RangeControl label="Số thẻ 'đang cập nhật'" value={pendingCount} min={0} max={8} onChange={(v) => setAttributes({ pendingCount: v })} />
					<TextControl label="Chữ nút trái" value={prevText} onChange={(v) => setAttributes({ prevText: v })} />
					<TextControl label="Chữ nút phải" value={nextText} onChange={(v) => setAttributes({ nextText: v })} />
				</PanelBody>
				<PanelBody title="Thẻ đang cập nhật" initialOpen={false}>
					<TextControl label="Tiêu đề" value={pendingTitle} onChange={(v) => setAttributes({ pendingTitle: v })} />
					<TextControl label="Chữ phụ" value={pendingText} onChange={(v) => setAttributes({ pendingText: v })} />
				</PanelBody>
				<PanelBody title="Hộp giới thiệu (khi bấm thẻ)" initialOpen={false}>
					<TextareaControl label="Ghi chú minh họa (để trống để ẩn)" value={previewNote} onChange={(v) => setAttributes({ previewNote: v })} />
					<TextareaControl label="Nội dung mặc định khi chưa có giới thiệu" value={dialogFallback} onChange={(v) => setAttributes({ dialogFallback: v })} />
				</PanelBody>
				<PanelBody title="Người trong hàng" initialOpen={false}>
					{items.map((it, i) => (
						<div key={i} style={{ marginBottom: 16, paddingBottom: 12, borderBottom: "1px solid #ddd" }}>
							<strong>#{i + 1} {it.name}</strong>
							<TextControl label="Vị trí ảnh (vd 50% 40%)" value={it.position || ""} onChange={(v) => setItem(i, { position: v })} />
							<TextareaControl label="Giới thiệu" value={it.bio || ""} onChange={(v) => setItem(i, { bio: v })} rows={4} />
							<TextControl label="TikTok URL" value={it.tiktok || ""} onChange={(v) => setItem(i, { tiktok: v })} />
							<TextControl label="Facebook URL" value={it.facebook || ""} onChange={(v) => setItem(i, { facebook: v })} />
							<TextControl label="Instagram URL" value={it.instagram || ""} onChange={(v) => setItem(i, { instagram: v })} />
							<Button isDestructive variant="link" onClick={() => setAttributes({ items: items.filter((_, k) => k !== i) })}>
								Xoá
							</Button>
						</div>
					))}
					<Button variant="secondary" onClick={() => setAttributes({ items: [...items, { name: "", linkText: "Xem câu chuyện", imageId: 0, imageUrl: "", position: "50% 40%", bio: "", tiktok: "", facebook: "", instagram: "" }] })}>
						+ Thêm người
					</Button>
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="cst__wrap">
					<RichText tagName="h2" className="cst__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn nhỏ (tuỳ chọn)" allowedFormats={[]} />
					<div className="cst__row-heading">
						<RichText tagName="h3" value={rowTitle} onChange={(v) => setAttributes({ rowTitle: v })} placeholder="Tiêu đề hàng" allowedFormats={[]} />
						<span className="cst__ctrl-mock">{prevText} · {nextText}</span>
					</div>
					<div className="cst__track">
						{items.map((it, i) => (
							<article className="cst__card" key={i}>
								<div className="cst__photo">
									<ImagePicker imageId={it.imageId} imageUrl={it.imageUrl} onSelect={(v) => setItem(i, v)} />
								</div>
								<div className="cst__overlay">
									<RichText tagName="span" className="cst__name" value={it.name} onChange={(v) => setItem(i, { name: v })} placeholder="TÊN" allowedFormats={[]} />
									<RichText tagName="span" className="cst__link" value={it.linkText} onChange={(v) => setItem(i, { linkText: v })} placeholder="Xem câu chuyện" allowedFormats={[]} />
								</div>
							</article>
						))}
						{Array.from({ length: pendingCount || 0 }).map((_, i) => (
							<article className="cst__card cst__card--pending" key={`p${i}`}>
								<span className="cst__mark">W.</span>
								<div>
									<h4>{pendingTitle}</h4>
									<p>{pendingText}</p>
								</div>
							</article>
						))}
					</div>
				</div>
			</section>
		</>
	);
}

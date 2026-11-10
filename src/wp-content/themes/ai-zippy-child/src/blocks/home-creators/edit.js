import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, titleAccent, linkText, linkUrl, creators = [] } = attributes;
	const blockProps = useBlockProps({ className: "hcr" });
	const setItem = (i, patch) => setAttributes({ creators: creators.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Link 'Xem tất cả'" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #nha-sang-tao)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
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
					<TextControl label="Link" value={linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
				</PanelBody>
				<ItemsPanel
					title="Nhà sáng tạo"
					hint="Thêm, xoá, đổi thứ tự và chọn ảnh cho từng người. Canvas là bản xem trước."
					items={creators}
					setItems={(v) => setAttributes({ creators: v })}
					itemLabel={(c) => dec(c.name)}
					makeItem={() => ({ name: "Tên", cta: "Xem câu chuyện", url: "", imageId: 0, imageUrl: "", position: "50% 50%" })}
					addLabel="+ Thêm nhà sáng tạo"
					fields={[
						{ key: "name", label: "Tên" },
						{ key: "imageId", type: "image", label: "Ảnh chân dung" },
						{ key: "cta", label: "Chữ link nhỏ" },
						{ key: "url", label: "Link khi bấm vào", type: "url", help: 'Dùng "/duong-dan/" cho trang trong site.' },
						{ key: "position", label: "Vị trí cắt ảnh (vd 50% 40%)", type: "url" },
					]}
				/>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hcr__wrap">
					<div className="hcr__top">
						<h2 className="hcr__title">
							<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Nhà sáng tạo" />{" "}
							<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Welive." />
						</h2>
						<span className="hcr__link-mock">
							<RichText tagName="span" style={{ fontSize: "inherit" }} value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Xem tất cả" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<div className="hcr__grid">
						{creators.map((c, i) => (
							<div className="hcr__card" key={i}>
								<div className="hcr__photo">{c.imageUrl ? <img src={c.imageUrl} alt="" style={{ objectPosition: c.position || "50% 50%" }} /> : <span className="hm-img-empty">Chưa có ảnh</span>}</div>
								<Html tag="h3" value={c.name} />
								<Html value={c.cta} />
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}

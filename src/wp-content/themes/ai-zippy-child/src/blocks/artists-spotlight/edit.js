import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, SelectControl } from "@wordpress/components";
import ItemsPanel, { Html, dec } from "../shared/ItemsPanel.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, subheading, artists = [], textAlign, layout, titleAccent, description, dividerText, note, dialogEyebrow, pendingText, dialogFallback } = attributes;
	const blockProps = useBlockProps({ className: `as has-text-align-${textAlign || "left"}` });
	const isWall = layout === "wall";
	const set = (key) => (v) => setAttributes({ [key]: v });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Kiểu hiển thị" initialOpen={true}>
					<SelectControl
						label="Kiểu hiển thị"
						value={layout || "grid"}
						options={[
							{ label: "Lưới ảnh", value: "grid" },
							{ label: "Bức tường danh vọng (khung vàng)", value: "wall" },
						]}
						onChange={set("layout")}
					/>
				</PanelBody>
				<ItemsPanel
					title="Nhà sáng tạo"
					hint="Bức tường: 3 người đầu đứng bục cao, những người sau xếp hàng nhỏ. Thêm, xoá, đổi thứ tự và nhập thông tin từng người ở đây."
					items={artists}
					setItems={(v) => setAttributes({ artists: v })}
					itemLabel={(a) => `${a.number || ""} ${dec(a.name)}`}
					makeItem={(n) => ({ name: "TÊN", number: String(n + 1).padStart(2, "0"), imageId: 0, imageUrl: "", position: "", dob: "", genre: "", bio: "", socials: { facebook: "", instagram: "", tiktok: "", threads: "", youtube: "" } })}
					addLabel="+ Thêm nhà sáng tạo"
					fields={[
						{ key: "name", label: "Tên" },
						{ key: "number", label: "Số thứ tự (vd 01)", type: "url" },
						{ key: "imageId", type: "image", label: "Ảnh chân dung (để trống = khung chờ)" },
						{ key: "position", label: "Vị trí cắt ảnh (vd 50% 42%)", type: "url" },
						{ key: "bio", label: "Giới thiệu (hiện khi bấm vào ảnh)", type: "textarea", rows: 5 },
						{ key: "socials.facebook", label: "Facebook", type: "url" },
						{ key: "socials.instagram", label: "Instagram", type: "url" },
						{ key: "socials.tiktok", label: "TikTok", type: "url" },
						{ key: "socials.threads", label: "Threads", type: "url" },
						{ key: "socials.youtube", label: "YouTube", type: "url" },
					]}
				/>
				{isWall && (
					<PanelBody title="Chữ của Bức tường danh vọng" initialOpen={false}>
						<TextControl label="Tiêu đề (dòng 1)" value={title} onChange={set("title")} />
						<TextControl label="Tiêu đề (dòng 2, màu vàng)" value={titleAccent || ""} onChange={set("titleAccent")} />
						<TextareaControl label="Mô tả bên phải (dùng <br> để xuống dòng)" value={description || ""} onChange={set("description")} rows={3} />
						<TextControl label="Chữ phân cách" value={dividerText || ""} onChange={set("dividerText")} />
						<TextControl label="Dòng ghi chú cuối" value={note || ""} onChange={set("note")} />
						<TextControl label="Nhãn trong hộp giới thiệu" value={dialogEyebrow || ""} onChange={set("dialogEyebrow")} />
						<TextControl label="Nội dung hộp giới thiệu khi chưa có bio" value={dialogFallback || ""} onChange={set("dialogFallback")} />
						<TextControl label="Chữ khi chưa có chân dung" value={pendingText || ""} onChange={set("pendingText")} />
					</PanelBody>
				)}
				<PanelBody title="Căn chữ" initialOpen={false}>
					<SelectControl
						label="Căn chữ"
						value={textAlign || "left"}
						options={[
							{ label: "Trái", value: "left" },
							{ label: "Giữa", value: "center" },
							{ label: "Phải", value: "right" },
						]}
						onChange={set("textAlign")}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="as__container">
					<div className="as__header-wrap">
						<div className="as__intro">
							<RichText tagName="h6" className="as__subheading" value={subheading} onChange={set("subheading")} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<RichText tagName="h2" className="as__heading" value={title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
						</div>
					</div>
					{isWall && <p style={{ color: "#8e8b7d", fontSize: 12, margin: "0 0 12px" }}>Bản xem trước đơn giản — khung vàng, bục và hộp giới thiệu chỉ hiện ở trang thật.</p>}
					<div className="as__grid">
						{artists.map((artist, index) => (
							<div key={index} className="as__card">
								<div className="as__card-img-wrap">{artist.imageUrl ? <img className="as__card-img" src={artist.imageUrl} alt="" /> : <div className="as__card-placeholder">Chưa có ảnh</div>}</div>
								<div className="as__card-info">
									<div className="as__card-meta">
										<span className="as__card-number">{artist.number}</span>
									</div>
									<Html tag="h3" className="as__card-name" value={artist.name} />
								</div>
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}

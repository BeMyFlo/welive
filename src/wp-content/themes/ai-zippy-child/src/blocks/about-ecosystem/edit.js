import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, buttonText, buttonUrl, divisions } = attributes;
	const blockProps = useBlockProps({ className: "abec" });

	const updateDivision = (index, key, value) => {
		const newDivisions = [...divisions];
		newDivisions[index] = { ...newDivisions[index], [key]: value };
		setAttributes({ divisions: newDivisions });
	};

	const addDivision = () => {
		const newDivisions = [
			...divisions,
			{ name: "Tiêu đề", description: "Mô tả ngắn.", imageId: 0, imageUrl: "" }
		];
		setAttributes({ divisions: newDivisions });
	};

	const removeDivision = (index) => {
		const newDivisions = divisions.filter((_, i) => i !== index);
		setAttributes({ divisions: newDivisions });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút" initialOpen={false}>
					<TextControl label="Chữ nút (để trống để ẩn)" value={buttonText} onChange={(val) => setAttributes({ buttonText: val })} />
					<TextControl label="Link nút" value={buttonUrl} onChange={(val) => setAttributes({ buttonUrl: val })} />
				</PanelBody>
				<PanelBody title="Giải pháp" initialOpen={true}>
					{divisions.map((div, index) => (
						<div key={index} style={{ borderBottom: "1px solid #ccc", paddingBottom: 15, marginBottom: 15 }}>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>#{index + 1}</strong>
								<Button isDestructive variant="link" onClick={() => removeDivision(index)}>
									Xoá
								</Button>
							</div>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={(media) => setAttributes({ divisions: divisions.map((d, i) => (i === index ? { ...d, imageId: media.id, imageUrl: media.url } : d)) })}
									allowedTypes={["image"]}
									value={div.imageId}
									render={({ open }) => (
										<Button variant="secondary" onClick={open} style={{ marginBottom: 10 }}>
											{div.imageUrl ? "Đổi ảnh" : "Chọn ảnh"}
										</Button>
									)}
								/>
							</MediaUploadCheck>
							<TextControl
								label="Tiêu đề"
								value={div.name}
								onChange={(val) => updateDivision(index, "name", val)}
							/>
							<TextareaControl
								label="Mô tả"
								value={div.description}
								onChange={(val) => updateDivision(index, "description", val)}
							/>
						</div>
					))}
					<Button variant="primary" onClick={addDivision} style={{ width: "100%", marginTop: 10 }}>
						Thêm giải pháp
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="abec__container">
					<div className="abec__header">
						<RichText
							tagName="h2"
							className="abec__title"
							value={title}
							onChange={(val) => setAttributes({ title: val })}
							placeholder="Title..."
						/>
						<RichText
							tagName="p"
							className="abec__subtitle"
							value={subtitle}
							onChange={(val) => setAttributes({ subtitle: val })}
							placeholder="Mô tả (có thể để trống)"
						/>
					</div>

					{buttonText !== "" && (
						<div className="abec__btn">
							<RichText tagName="span" value={buttonText} onChange={(val) => setAttributes({ buttonText: val })} placeholder="Chữ nút" allowedFormats={[]} />
						</div>
					)}
					<div className="abec__grid">
						{divisions.map((div, index) => (
							<div key={index} className="abec__item">
								<div className="abec__media">{div.imageUrl ? <img src={div.imageUrl} alt="" /> : <span className="abec__media-empty">Ảnh</span>}</div>
								<RichText tagName="h3" className="abec__name" value={div.name} onChange={(val) => updateDivision(index, "name", val)} placeholder="Tiêu đề" allowedFormats={[]} />
								<RichText tagName="p" className="abec__desc" value={div.description} onChange={(val) => updateDivision(index, "description", val)} placeholder="Mô tả" allowedFormats={[]} />
							</div>
						))}
					</div>
					<Button variant="secondary" onClick={addDivision} style={{ width: "100%", marginTop: 20 }}>
						+ Thêm giải pháp
					</Button>
				</div>
			</div>
		</>
	);
}

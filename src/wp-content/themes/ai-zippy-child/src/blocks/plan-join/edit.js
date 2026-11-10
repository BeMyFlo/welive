import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const a = attributes;
	const blockProps = useBlockProps({ className: "pjn" });
	const set = (key) => (v) => setAttributes({ [key]: v });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Anchor" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #dang-ky)" value={a.anchor || ""} onChange={set("anchor")} />
				</PanelBody>
				<PanelBody title="Gợi ý trong ô nhập" initialOpen={false}>
					<TextControl label="Họ và tên" value={a.placeholderName} onChange={set("placeholderName")} />
					<TextControl label="Số điện thoại / Zalo" value={a.placeholderContact} onChange={set("placeholderContact")} />
					<TextControl label="TikTok" value={a.placeholderChannel} onChange={set("placeholderChannel")} />
					<TextControl label="Mục tiêu" value={a.placeholderGoal} onChange={set("placeholderGoal")} />
				</PanelBody>
				<PanelBody title="Thông báo sau khi gửi" initialOpen={false}>
					<TextControl label="Thành công" value={a.successText} onChange={set("successText")} />
					<TextControl label="Lỗi" value={a.errorText} onChange={set("errorText")} />
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="pjn__wrap">
					<div className="pjn__copy">
						<RichText tagName="span" className="pjn__pill" value={a.pill} onChange={set("pill")} placeholder="Nhãn" allowedFormats={[]} />
						<RichText tagName="h2" className="pjn__title" value={a.title} onChange={set("title")} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" className="pjn__accent" value={a.titleAccent} onChange={set("titleAccent")} placeholder="Dòng màu cam" allowedFormats={[]} />
						<RichText tagName="p" className="pjn__text" value={a.text} onChange={set("text")} placeholder="Mô tả" />
						<RichText tagName="p" className="pjn__note" value={a.note} onChange={set("note")} placeholder="Ghi chú" allowedFormats={[]} />
					</div>
					<div className="pjn__form">
						<RichText tagName="label" value={a.labelName} onChange={set("labelName")} placeholder="Nhãn ô 1" allowedFormats={[]} />
						<input disabled placeholder={a.placeholderName} />
						<div className="pjn__row">
							<div>
								<RichText tagName="label" value={a.labelContact} onChange={set("labelContact")} placeholder="Nhãn ô 2" allowedFormats={[]} />
								<input disabled placeholder={a.placeholderContact} />
							</div>
							<div>
								<RichText tagName="label" value={a.labelChannel} onChange={set("labelChannel")} placeholder="Nhãn ô 3" allowedFormats={[]} />
								<input disabled placeholder={a.placeholderChannel} />
							</div>
						</div>
						<RichText tagName="label" value={a.labelGoal} onChange={set("labelGoal")} placeholder="Nhãn ô 4" allowedFormats={[]} />
						<textarea disabled placeholder={a.placeholderGoal} />
						<span className="pjn__submit">
							<RichText tagName="span" value={a.btnText} onChange={set("btnText")} placeholder="Chữ nút" allowedFormats={[]} />
						</span>
						<RichText tagName="p" className="pjn__form-note" value={a.formNote} onChange={set("formNote")} placeholder="Ghi chú dưới nút" allowedFormats={[]} />
					</div>
				</div>
			</section>
		</>
	);
}

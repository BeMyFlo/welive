import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, description, milestones } = attributes;
	const blockProps = useBlockProps({ className: "abm" });

	const updateMilestone = (index, key, value) => {
		const newMilestones = [...milestones];
		newMilestones[index] = { ...newMilestones[index], [key]: value };
		setAttributes({ milestones: newMilestones });
	};

	const addMilestone = () => {
		const newMilestones = [
			...milestones,
			{ year: "2026", label: "Label Title", detail: "Detail description", date: "Month/Year" }
		];
		setAttributes({ milestones: newMilestones });
	};

	const removeMilestone = (index) => {
		const newMilestones = milestones.filter((_, i) => i !== index);
		setAttributes({ milestones: newMilestones });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Milestones Timeline Management" initialOpen={true}>
					{milestones.map((milestone, index) => (
						<div key={index} style={{ borderBottom: "1px solid #ccc", paddingBottom: 15, marginBottom: 15 }}>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>Milestone #{index + 1}</strong>
								<Button isDestructive variant="link" onClick={() => removeMilestone(index)}>
									Remove
								</Button>
							</div>
							<TextControl
								label="Year"
								value={milestone.year}
								onChange={(val) => updateMilestone(index, "year", val)}
							/>
							<TextControl
								label="Ngày/tháng (không bắt buộc)"
								value={milestone.date}
								onChange={(val) => updateMilestone(index, "date", val)}
							/>
							<TextControl
								label="Title Label"
								value={milestone.label}
								onChange={(val) => updateMilestone(index, "label", val)}
							/>
							<TextControl
								label="Subtitle/Detail"
								value={milestone.detail}
								onChange={(val) => updateMilestone(index, "detail", val)}
							/>
						</div>
					))}
					<Button variant="primary" onClick={addMilestone} style={{ width: "100%", marginTop: 10 }}>
						Add New Milestone
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="abm__container">
					<div className="abm__flex-header">
						<div className="abm__intro">
							<RichText
								tagName="h2"
								className="abm__heading"
								value={title}
								onChange={(val) => setAttributes({ title: val })}
								placeholder="Title..."
							/>
							<RichText
								tagName="p"
								className="abm__desc"
								value={description}
								onChange={(val) => setAttributes({ description: val })}
								placeholder="Timeline description..."
							/>
						</div>
					</div>

					<div className="abm__timeline-editor">
						{milestones.map((m, idx) => (
								<div key={idx} style={{ padding: 15, border: "1px solid #333", borderRadius: 8, margin: "10px 0", backgroundColor: "#111" }}>
									<RichText tagName="div" style={{ color: "#EC4600", fontSize: "1.25rem", fontWeight: "bold" }} value={m.year} onChange={(val) => updateMilestone(idx, "year", val)} placeholder="Số liệu (vd 5M+)" allowedFormats={[]} />
									<RichText tagName="div" style={{ fontWeight: "bold", color: "#fff", marginTop: 5 }} value={m.label} onChange={(val) => updateMilestone(idx, "label", val)} placeholder="Label" allowedFormats={[]} />
									<RichText tagName="div" style={{ color: "#aaa", fontSize: "0.9rem" }} value={m.detail} onChange={(val) => updateMilestone(idx, "detail", val)} placeholder="Detail" allowedFormats={[]} />
									<RichText tagName="div" style={{ color: "#888", fontSize: "0.8rem" }} value={m.date} onChange={(val) => updateMilestone(idx, "date", val)} placeholder="Ngày (không bắt buộc)" allowedFormats={[]} />
								</div>
							))}
							<Button variant="secondary" onClick={addMilestone} style={{ marginTop: 10, width: "100%" }}>
							+ Add Milestone Card
						</Button>
					</div>
				</div>
			</div>
		</>
	);
}

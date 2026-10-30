import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, divisions } = attributes;
	const blockProps = useBlockProps({ className: "abec" });

	const updateDivision = (index, key, value) => {
		const newDivisions = [...divisions];
		newDivisions[index] = { ...newDivisions[index], [key]: value };
		setAttributes({ divisions: newDivisions });
	};

	const addDivision = () => {
		const newDivisions = [
			...divisions,
			{ name: "New Division", description: "Division description text." }
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
				<PanelBody title="Ecosystem Divisions" initialOpen={true}>
					{divisions.map((div, index) => (
						<div key={index} style={{ borderBottom: "1px solid #ccc", paddingBottom: 15, marginBottom: 15 }}>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>Division #{index + 1}</strong>
								<Button isDestructive variant="link" onClick={() => removeDivision(index)}>
									Remove
								</Button>
							</div>
							<TextControl
								label="Division Name"
								value={div.name}
								onChange={(val) => updateDivision(index, "name", val)}
							/>
							<TextareaControl
								label="Description"
								value={div.description}
								onChange={(val) => updateDivision(index, "description", val)}
							/>
						</div>
					))}
					<Button variant="primary" onClick={addDivision} style={{ width: "100%", marginTop: 10 }}>
						Add New Division
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
							placeholder="Subtitle..."
						/>
					</div>

					<div className="abec__divisions-list">
						{divisions.map((div, index) => (
							<div key={index} style={{ padding: 15, border: "1px solid #333", borderRadius: 8, margin: "10px 0", backgroundColor: "#111" }}>
								<div style={{ fontWeight: "bold", color: "#EC4600" }}>{div.name}</div>
								<div style={{ color: "#aaa", fontSize: "0.9rem", marginTop: 5 }}>{div.description}</div>
							</div>
						))}
						<Button variant="secondary" onClick={addDivision} style={{ width: "100%", marginTop: 10 }}>
							+ Add Division Item
						</Button>
					</div>
				</div>
			</div>
		</>
	);
}

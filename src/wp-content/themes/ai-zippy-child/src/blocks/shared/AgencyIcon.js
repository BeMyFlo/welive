// Line icons shared by the agency-* blocks (keep in sync with render.php icon maps).
const PATHS = {
	search: <><circle cx="11" cy="11" r="7" /><path d="M20 20l-3.5-3.5" /></>,
	users: <><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></>,
	chart: <path d="M5 21V12M12 21V5M19 21V9" />,
	mail: <><rect x="2" y="4" width="20" height="16" rx="2" /><path d="m22 7-10 6L2 7" /></>,
	phone: <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />,
	pin: <><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></>,
};

export const ICON_OPTIONS = {
	services: [
		{ label: "Search", value: "search" },
		{ label: "Users", value: "users" },
		{ label: "Chart", value: "chart" },
	],
	contact: [
		{ label: "Email", value: "mail" },
		{ label: "Phone", value: "phone" },
		{ label: "Location", value: "pin" },
	],
};

export default function AgencyIcon({ name, size = 24 }) {
	return (
		<svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
			{PATHS[name] || PATHS.search}
		</svg>
	);
}

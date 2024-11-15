export const ColorTags = {
	SLATE: 'SLATE',
	GRAY: 'GRAY',
	ZINC: 'ZINC',
	NEUTRAL: 'NEUTRAL',
	STONE: 'STONE',
	RED: 'RED',
	ORANGE: 'ORANGE',
	AMBER: 'AMBER',
	YELLOW: 'YELLOW',
	LIME: 'LIME',
	GREEN: 'GREEN',
	EMERALD: 'EMERALD',
	TEAL: 'TEAL',
	CYAN: 'CYAN',
	SKY: 'SKY',
	BLUE: 'BLUE',
	INDIGO: 'INDIGO',
	VIOLET: 'VIOLET',
	PURPLE: 'PURPLE',
	FUCHSIA: 'FUCHSIA',
	PINK: 'PINK',
	ROSE: 'ROSE',
} as const;

export const DevTags = {
	ARCHITECTURE: 'ARCHITECTURE',
	HTML: 'HTML',
	CSS: 'CSS',
	JAVASCRIPT: 'JAVASCRIPT',
	PHP: 'PHP',
	PHPUNIT: 'PHPUNIT',
	GIT: 'GIT',
	ZEND: 'ZEND',
	SYMFONY: 'SYMFONY',
	POSTGRESQL: 'POSTGRESQL',
	MYSQL: 'MYSQL',
	MARIADB: 'MARIADB',
	DOCKER: 'DOCKER',
	NODEJS: 'NODEJS',
	FLUTTER: 'FLUTTER',
	TYPESCRIPT: 'TYPESCRIPT',
	GRAPHQL: 'GRAPHQL',
	TAILWINDCSS: 'TAILWINDCSS',
	GO: 'GO',
	GOOGLECLOUD: 'GOOGLECLOUD',
	ASTRO: 'ASTRO',
	ANGULAR: 'ANGULAR',
	VUEJS: 'VUEJS',
	VAGRANT: 'VAGRANT',
	IONIC: 'IONIC',
	LARAVEL: 'LARAVEL',
	APIPLATFORM: 'APIPLATFORM',
	PYTHON: 'PYTHON',
	OTHER: 'OTHER',
} as const;

export const colorToClassMap = {
	[ColorTags.SLATE]: 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300',
	[ColorTags.GRAY]: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
	[ColorTags.ZINC]: 'bg-zinc-100 text-zinc-800 dark:bg-zinc-900 dark:text-zinc-300',
	[ColorTags.NEUTRAL]: 'bg-neutral-100 text-neutral-800 dark:bg-neutral-900 dark:text-neutral-300',
	[ColorTags.STONE]: 'bg-stone-100 text-stone-800 dark:bg-stone-900 dark:text-stone-300',
	[ColorTags.RED]: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
	[ColorTags.ORANGE]: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
	[ColorTags.AMBER]: 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
	[ColorTags.YELLOW]: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
	[ColorTags.LIME]: 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
	[ColorTags.GREEN]: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
	[ColorTags.EMERALD]: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
	[ColorTags.TEAL]: 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300',
	[ColorTags.CYAN]: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
	[ColorTags.SKY]: 'bg-sky-100 text-sky-800 dark:bg-sky-900 dark:text-sky-300',
	[ColorTags.BLUE]: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
	[ColorTags.INDIGO]: 'bg-indigo-400 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
	[ColorTags.VIOLET]: 'bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-300',
	[ColorTags.PURPLE]: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
	[ColorTags.FUCHSIA]: 'bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-900 dark:text-fuchsia-300',
	[ColorTags.PINK]: 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
	[ColorTags.ROSE]: 'bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-300',
};

export const devColorToClassMap = {
	[DevTags.ARCHITECTURE]: ColorTags.NEUTRAL,
	[DevTags.HTML]: ColorTags.AMBER,
	[DevTags.CSS]: ColorTags.BLUE,
	[DevTags.JAVASCRIPT]: ColorTags.YELLOW,
	[DevTags.PHP]: ColorTags.SKY,
	[DevTags.PHPUNIT]: ColorTags.CYAN,
	[DevTags.GIT]: ColorTags.ORANGE,
	[DevTags.ZEND]: ColorTags.GREEN,
	[DevTags.SYMFONY]: ColorTags.EMERALD,
	[DevTags.POSTGRESQL]: ColorTags.BLUE,
	[DevTags.MYSQL]: ColorTags.BLUE,
	[DevTags.MARIADB]: ColorTags.BLUE,
	[DevTags.DOCKER]: ColorTags.INDIGO,
	[DevTags.NODEJS]: ColorTags.LIME,
	[DevTags.FLUTTER]: ColorTags.CYAN,
	[DevTags.TYPESCRIPT]: ColorTags.BLUE,
	[DevTags.GRAPHQL]: ColorTags.PINK,
	[DevTags.TAILWINDCSS]: ColorTags.INDIGO,
	[DevTags.GO]: ColorTags.BLUE,
	[DevTags.GOOGLECLOUD]: ColorTags.CYAN,
	[DevTags.ASTRO]: ColorTags.ZINC,
	[DevTags.ANGULAR]: ColorTags.RED,
	[DevTags.VUEJS]: ColorTags.TEAL,
	[DevTags.VAGRANT]: ColorTags.INDIGO,
	[DevTags.IONIC]: ColorTags.BLUE,
	[DevTags.LARAVEL]: ColorTags.ROSE,
	[DevTags.APIPLATFORM]: ColorTags.ROSE,
	[DevTags.PYTHON]: ColorTags.YELLOW,
	[DevTags.OTHER]: ColorTags.ZINC,
};

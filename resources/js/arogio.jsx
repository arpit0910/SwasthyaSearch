import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { ThemeProvider, createTheme, Box, Container, Stack as MuiStack, Typography as MuiTypography, Button, IconButton, Paper, Card, CardActionArea, Chip, Tabs, Tab, TextField, InputAdornment, MenuItem, Menu, Drawer, List, ListItemButton, ListItemText, Divider, Alert, Accordion, AccordionSummary, AccordionDetails, Dialog, DialogTitle, DialogContent, DialogActions, Rating, CircularProgress, BottomNavigation, BottomNavigationAction } from '@mui/material';
import Search from '@mui/icons-material/Search';
import LocalHospital from '@mui/icons-material/LocalHospitalOutlined';
import Health from '@mui/icons-material/HealthAndSafetyOutlined';
import Blood from '@mui/icons-material/BloodtypeOutlined';
import Medicine from '@mui/icons-material/MedicationOutlined';
import Leaf from '@mui/icons-material/SpaOutlined';
import Doctor from '@mui/icons-material/MedicalServicesOutlined';
import Heart from '@mui/icons-material/FavoriteBorder';
import Brain from '@mui/icons-material/PsychologyOutlined';
import Baby from '@mui/icons-material/ChildCare';
import Eye from '@mui/icons-material/VisibilityOutlined';
import Bone from '@mui/icons-material/AccessibilityNew';
import Woman from '@mui/icons-material/Female';
import Arrow from '@mui/icons-material/ArrowForward';
import Down from '@mui/icons-material/ExpandMore';
import Location from '@mui/icons-material/LocationOnOutlined';
import Near from '@mui/icons-material/MyLocation';
import Close from '@mui/icons-material/Close';
import Robot from '@mui/icons-material/SmartToyOutlined';
import Check from '@mui/icons-material/VerifiedUserOutlined';
import Warning from '@mui/icons-material/WarningAmber';
import Book from '@mui/icons-material/MenuBookOutlined';
import Video from '@mui/icons-material/VideocamOutlined';
import Calendar from '@mui/icons-material/CalendarMonthOutlined';
import People from '@mui/icons-material/PeopleOutlined';
import MenuIcon from '@mui/icons-material/Menu';
import Moon from '@mui/icons-material/DarkModeOutlined';
import Sun from '@mui/icons-material/LightModeOutlined';
import HomeIcon from '@mui/icons-material/HomeOutlined';
import Headphones from '@mui/icons-material/HeadphonesOutlined';
import Games from '@mui/icons-material/ExtensionOutlined';
import './arogio.css';
import { Directory } from './pages/directory';
import { Symptoms } from './pages/symptoms';
import { Remedies } from './pages/remedies';
import { Jeeva } from './pages/jeeva';

const config = window.arogioDesign;
const data = window.arogioHome || {};
const hi = config.locale === 'hi';
const t = (en, hindi) => hi ? hindi : en;
// MUI 9 uses sx for system properties; keep shared layout primitives explicit.
function Stack({ gap, alignItems, justifyContent, flexWrap, sx, ...props }) {
  return <MuiStack {...props} sx={{ minWidth: 0, gap, alignItems, justifyContent, flexWrap, ...sx }} />;
}
function Typography({ fontWeight, sx, ...props }) {
  return <MuiTypography {...props} sx={{ fontWeight, ...sx }} />;
}
const url = (name, params = {}) => {
  const target = new URL(config.routes[name], location.href);
  Object.entries(params).forEach(([key, value]) => { if (value !== '' && value != null) target.searchParams.set(key, value); });
  return target.href;
};
function storedCity() {
  const cityParam = new URLSearchParams(location.search);
  try { return cityParam.get('city[]') || cityParam.get('city') || localStorage.getItem('arogio_selected_city') || config.city; } catch { return config.city; }
}
function useCity() {
  const [city, setCity] = useState(storedCity);
  useEffect(() => { const sync = event => setCity(event.detail || storedCity()); window.addEventListener('arogio:city', sync); return () => window.removeEventListener('arogio:city', sync); }, []);
  return [city, (value) => {
    const clean = value.trim();
    if (!clean) return;
    try { localStorage.setItem('arogio_selected_city', clean); } catch { /* Browsing remains available without storage. */ }
    setCity(clean);
    window.selectChatbotCity?.(clean);
    window.dispatchEvent(new CustomEvent('arogio:city', { detail: clean }));
    const directory = ['doctors.index', 'hospitals.index', 'blood_banks.index'].find(name => new URL(url(name)).pathname === location.pathname);
    if (directory) {
      const target = new URL(location.href);
      target.searchParams.delete('city'); target.searchParams.delete('city[]'); target.searchParams.delete('page');
      target.searchParams.set('city[]', clean);
      location.href = target.href;
    }
  }];
}
function askJeeva(question = '') {
  window.dispatchEvent(new CustomEvent('arogio:chat', { detail: { open: true, question } }));
}
function DesignTheme({ children }) {
  const [dark, setDark] = useState(document.documentElement.classList.contains('dark'));
  useEffect(() => {
    const observer = new MutationObserver(() => setDark(document.documentElement.classList.contains('dark')));
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    return () => observer.disconnect();
  }, []);
  const theme = React.useMemo(() => createTheme({
    palette: { mode: dark ? 'dark' : 'light', primary: { main: dark ? '#7ad7c6' : '#00796b' }, secondary: { main: dark ? '#a9c7ff' : '#1565c0' }, background: { default: dark ? '#101e24' : '#F7FAFA', paper: dark ? '#172b34' : '#fff' }, text: { primary: dark ? '#e4f0f4' : '#172B3A', secondary: dark ? '#b4c8ce' : '#546E7A' }, divider: dark ? '#31464f' : '#E2E8EA' },
    typography: { fontFamily: 'Urbanist, "Noto Sans Devanagari", sans-serif', h1: { fontSize: '2.6rem', fontWeight: 750, lineHeight: 1.25, letterSpacing: '-.025em' }, h2: { fontSize: '1.85rem', fontWeight: 750, lineHeight: 1.35 }, h3: { fontSize: '1.15rem', fontWeight: 750, lineHeight: 1.45 }, button: { textTransform: 'none', fontWeight: 700 }, body1: { lineHeight: 1.6 }, body2: { lineHeight: 1.6 } },
    shape: { borderRadius: 10 },
    components: {
      MuiButton: { defaultProps: { disableElevation: true }, styleOverrides: { root: { minHeight: 42, borderRadius: 8 }, sizeSmall: { minHeight: 36 } } },
      MuiIconButton: { styleOverrides: { root: { minWidth: 44, minHeight: 44 } } },
      MuiPaper: { styleOverrides: { root: { backgroundImage: 'none' } } },
      MuiCard: { styleOverrides: { root: { border: `1px solid ${dark ? '#31464f' : '#E2E8EA'}`, boxShadow: '0 2px 8px rgba(23,43,58,.035)' } } },
      MuiTab: { styleOverrides: { root: { textTransform: 'none', fontWeight: 700, minHeight: 44 } } },
      MuiContainer: { styleOverrides: { maxWidthLg: { '@media (min-width:1200px)': { maxWidth: 1240 } } } },
      MuiAccordion: { defaultProps: { disableGutters: true, elevation: 0 }, styleOverrides: { root: { border: `1px solid ${dark ? '#31464f' : '#E2E8EA'}`, '&:before': { display: 'none' } } } },
    },
  }), [dark]);
  return <ThemeProvider theme={theme}>{children}</ThemeProvider>;
}
const groups = [
  [t('Find Care', 'सेवा खोजें'), [['doctors.index', t('Doctors', 'डॉक्टर')], ['hospitals.index', t('Hospitals & Clinics', 'अस्पताल और क्लिनिक')], ['blood_banks.index', t('Blood Banks', 'ब्लड बैंक')], ['departments.index', t('Departments', 'विभाग')]]],
  [t('Health Info', 'स्वास्थ्य जानकारी'), [['medicines.index', t('Medicines', 'दवाइयाँ')], ['articles.index', t('Health Articles', 'स्वास्थ्य लेख')], ['diseases.index', t('Diseases', 'रोग')], ['nani-dadi.index', t('Nani Dadi Ke Nuskhe', 'नानी दादी के नुस्खे')]]],
  [t('Wellness', 'वेलनेस'), [['activities.index', t('Activities', 'गतिविधियाँ')], ['quizzes.index', t('Health Quizzes', 'स्वास्थ्य प्रश्नोत्तरी')], ['support.crisis', t('Crisis Support', 'संकट सहायता')]]],
];
function CityPicker({ compact = false }) {
  const [city, setCity] = useCity();
  const [open, setOpen] = useState(false);
  const [value, setValue] = useState(city);
  return <>
    <Button size="small" startIcon={<Location />} endIcon={<Down />} onClick={() => { setValue(city); setOpen(true); }} sx={{ bgcolor: 'action.hover', color: 'text.primary', maxWidth: compact ? 140 : 210, whiteSpace: 'nowrap' }}><Box component="span" sx={{ overflow: 'hidden', textOverflow: 'ellipsis' }}>{city}</Box></Button>
    <Dialog open={open} onClose={() => setOpen(false)} fullWidth maxWidth="xs">
      <Box component="form" onSubmit={e => { e.preventDefault(); setCity(value); setOpen(false); }}>
        <DialogTitle>{t('Choose your city', 'अपना शहर चुनें')}</DialogTitle>
        <DialogContent><Typography color="text.secondary" sx={{ mb: 2 }}>{t('Find healthcare information relevant to your location. Coverage varies by city.', 'अपने शहर से जुड़ी स्वास्थ्य जानकारी खोजें। उपलब्ध जानकारी शहर के अनुसार अलग हो सकती है।')}</Typography><TextField autoFocus fullWidth required label={t('City', 'शहर')} value={value} onChange={e => setValue(e.target.value)} slotProps={{ htmlInput: { maxLength: 100 } }} /><Stack direction="row" flexWrap="wrap" gap={1} sx={{ mt: 2 }}>{[config.city, 'Delhi', 'Mumbai', 'Bangalore'].filter((v, i, a) => a.indexOf(v) === i).map(c => <Chip key={c} label={c} onClick={() => setValue(c)} />)}</Stack></DialogContent>
        <DialogActions><Button onClick={() => setOpen(false)}>{t('Cancel', 'रद्द करें')}</Button><Button type="submit" variant="contained" disabled={!value.trim()}>{t('Use this city', 'यह शहर चुनें')}</Button></DialogActions>
      </Box>
    </Dialog>
  </>;
}
function Header() {
  const [drawer, setDrawer] = useState(false);
  const [menu, setMenu] = useState(null);
  const [dark, setDark] = useState(document.documentElement.classList.contains('dark'));
  const toggleTheme = () => { window.toggleThemeMode?.(); setDark(document.documentElement.classList.contains('dark')); };
  return <>
    <Paper square elevation={0} component="header" sx={{ borderBottom: 1, borderColor: 'divider' }}>
      <Container><Stack direction="row" alignItems="center" gap={{ xs: .5, md: 1 }} sx={{ minHeight: 72 }}>
        <a href={url('home')} className="design-logo"><img src={dark ? config.logoDark : config.logo} alt="Arogio" width="126" height="40" /></a>
        <Box sx={{ display: { xs: 'none', md: 'block' } }}><CityPicker compact /></Box>
        <Stack component="nav" aria-label={t('Main navigation', 'मुख्य नेविगेशन')} direction="row" sx={{ display: { xs: 'none', lg: 'flex' }, ml: 1 }}>
          {groups.map(([label], index) => <Button key={label} color="inherit" size="small" endIcon={<Down />} aria-haspopup="menu" aria-expanded={menu?.index === index ? 'true' : undefined} onClick={e => setMenu({ anchor: e.currentTarget, index })}>{label}</Button>)}
          <Button color="inherit" size="small" href={url('consultations.index')}>{t('Consultations', 'परामर्श')}</Button>
        </Stack>
        <Box sx={{ flexGrow: 1 }} />
        <Button href={url('emergency')} size="small" color="error" startIcon={<Health />} sx={{ bgcolor: 'rgba(211,47,47,.08)', fontSize: 12 }}>{t('Emergency', 'आपातकाल')}</Button>
        <Button startIcon={<Robot />} size="small" onClick={() => askJeeva()} sx={{ display: { xs: 'none', md: 'inline-flex' }, bgcolor: 'action.hover' }}>{t('Ask Jeeva', 'जीवा से पूछें')}</Button>
        <Box component="form" action={url('switch.locale')} method="post"><input type="hidden" name="_token" value={config.csrf} /><input type="hidden" name="locale" value={hi ? 'en' : 'hi'} /><Button type="submit" size="small" aria-label={hi ? 'Switch to English' : 'हिंदी में बदलें'} sx={{ minWidth: 42 }}>{hi ? 'EN' : 'हिंदी'}</Button></Box>
        <IconButton aria-label={t('Toggle color theme', 'रंग थीम बदलें')} onClick={toggleTheme} sx={{ display: { xs: 'none', sm: 'inline-flex' } }}>{dark ? <Sun fontSize="small" /> : <Moon fontSize="small" />}</IconButton>
        <IconButton onClick={() => setDrawer(true)} aria-label={t('Open navigation menu', 'नेविगेशन मेनू खोलें')} sx={{ display: { lg: 'none' } }}><MenuIcon /></IconButton>
      </Stack></Container>
    </Paper>
    <Menu anchorEl={menu?.anchor} open={Boolean(menu)} onClose={() => setMenu(null)}>{menu && groups[menu.index][1].map(([route, label]) => <MenuItem key={route} component="a" href={url(route)}>{label}</MenuItem>)}</Menu>
    <Drawer anchor="right" open={drawer} onClose={() => setDrawer(false)}><Box sx={{ width: 'min(340px, 90vw)', p: 2 }}><Stack direction="row" justifyContent="space-between" alignItems="center"><Typography variant="h3">Arogio</Typography><IconButton aria-label={t('Close navigation', 'नेविगेशन बंद करें')} onClick={() => setDrawer(false)}><Close /></IconButton></Stack><Box sx={{ my: 2 }}><CityPicker /></Box>{groups.map(([label, links]) => <Box key={label}><Typography variant="overline" color="text.secondary">{label}</Typography><List dense>{links.map(([route, title]) => <ListItemButton component="a" href={url(route)} key={route}><ListItemText primary={title} /></ListItemButton>)}</List><Divider /></Box>)}<List>{[['consultations.index', t('Video consultation', 'वीडियो परामर्श')], ['suggestions.create', t('Suggest a provider', 'प्रदाता सुझाएँ')], ['about', t('About us', 'हमारे बारे में')], ['contact', t('Contact', 'संपर्क')]].map(([route, title]) => <ListItemButton component="a" href={url(route)} key={route}><ListItemText primary={title} /></ListItemButton>)}</List><Button startIcon={dark ? <Sun /> : <Moon />} onClick={toggleTheme}>{t('Change theme', 'थीम बदलें')}</Button></Box></Drawer>
    <Paper className="design-bottom-nav" elevation={3}><BottomNavigation showLabels value={location.pathname === new URL(url('home')).pathname ? 0 : false}><BottomNavigationAction label={t('Home', 'होम')} icon={<HomeIcon />} href={url('home')} /><BottomNavigationAction label={t('Find Care', 'सेवा खोजें')} icon={<Search />} href={url('doctors.index')} /><BottomNavigationAction label={t('Jeeva', 'जीवा')} icon={<Robot />} onClick={() => askJeeva()} /><BottomNavigationAction label={t('More', 'और')} icon={<MenuIcon />} onClick={() => setDrawer(true)} /></BottomNavigation></Paper>
  </>;
}
function SectionHeading({ title, subtitle, link, linkText }) {
  return <Stack direction={{ xs: 'column', sm: 'row' }} justifyContent="space-between" alignItems={{ xs: 'flex-start', sm: 'center' }} gap={1} sx={{ mb: 3 }}><Box><Typography variant="h2" sx={{ fontSize: { xs: 25, md: 30 } }}>{title}</Typography>{subtitle && <Typography color="text.secondary" sx={{ mt: .5 }}>{subtitle}</Typography>}</Box>{link && <Button href={link} endIcon={<Arrow />} sx={{ whiteSpace: 'nowrap' }}>{linkText || t('View all', 'सभी देखें')}</Button>}</Stack>;
}
function IconTile({ icon: Icon, color = '#00796b', tint = '#E0F2F1' }) { return <Box sx={{ width: 48, height: 48, bgcolor: tint, color, borderRadius: 2, display: 'grid', placeItems: 'center', flexShrink: 0 }}><Icon sx={{ fontSize: 27 }} /></Box>; }
const services = [
  ['doctors.index', Doctor, t('Find Doctors', 'डॉक्टर खोजें'), t('Discover specialists and general physicians in your city.', 'अपने शहर में विशेषज्ञ और सामान्य चिकित्सक खोजें।'), t('Browse doctors', 'डॉक्टर देखें'), '#00796b', '#E0F2F1'],
  ['hospitals.index', LocalHospital, t('Hospitals & Clinics', 'अस्पताल और क्लिनिक'), t('Explore healthcare facilities, services, and scheme information.', 'स्वास्थ्य सुविधाएँ, सेवाएँ और योजना की जानकारी देखें।'), t('Find a hospital', 'अस्पताल खोजें'), '#1565c0', '#E3F2FD'],
  ['blood_banks.index', Blood, t('Blood Banks', 'ब्लड बैंक'), t('Find blood banks near you. Call to confirm availability.', 'नज़दीकी ब्लड बैंक खोजें। उपलब्धता के लिए कॉल करें।'), t('Locate blood banks', 'ब्लड बैंक खोजें'), '#c62828', '#FFEBEE'],
  ['symptom', Health, t('Symptom Check', 'लक्षण जाँच'), t('Understand your symptoms and explore relevant specialties.', 'अपने लक्षण समझें और संबंधित विशेषज्ञता जानें।'), t('Check symptoms', 'लक्षण जाँचें'), '#00796b', '#E0F2F1'],
  ['medicines.index', Medicine, t('Medicine Directory', 'दवा निर्देशिका'), t('Understand uses, precautions, and side effects of medicines.', 'दवाओं के उपयोग, सावधानियाँ और दुष्प्रभाव समझें।'), t('Explore medicines', 'दवाइयाँ देखें'), '#1565c0', '#E3F2FD'],
  ['nani-dadi.index', Leaf, t('Nani Dadi Ke Nuskhe', 'नानी दादी के नुस्खे'), t('Traditional home remedies with evidence and safety guidance.', 'प्रमाण और सुरक्षा जानकारी के साथ पारंपरिक घरेलू नुस्खे।'), t('Discover remedies', 'नुस्खे देखें'), '#843f29', '#FBE9E7'],
];
function SearchPanel() {
  const [tab, setTab] = useState(0);
  const [query, setQuery] = useState('');
  const [kind, setKind] = useState('doctors.index');
  const [city] = useCity();
  const [gps, setGps] = useState(false);
  const [coords, setCoords] = useState(null);
  const [notice, setNotice] = useState('');
  const [results, setResults] = useState(null);
  const [loading, setLoading] = useState(false);
  const [searchError, setSearchError] = useState('');
  const abort = React.useRef(null);
  useEffect(() => () => abort.current?.abort(), []);
  function locate() {
    if (!navigator.geolocation) { setNotice(t('Location is unavailable. Please choose your city.', 'स्थान उपलब्ध नहीं है। कृपया शहर चुनें।')); return; }
    setGps(true);
    navigator.geolocation.getCurrentPosition(p => { setCoords({ user_lat: p.coords.latitude, user_lng: p.coords.longitude }); setGps(false); setNotice(t('Location ready. Directory results can use your position.', 'स्थान मिल गया। निर्देशिका आपके स्थान का उपयोग कर सकती है।')); }, () => { setGps(false); setNotice(t('Location could not be accessed. You can still search by city.', 'स्थान नहीं मिल सका। आप शहर के अनुसार खोज सकते हैं।')); }, { timeout: 10000, maximumAge: 300000 });
  }
  async function submit(e) {
    e.preventDefault();
    if (tab === 1) { askJeeva(query); return; }
    if (kind !== 'doctors.index' || !query.trim()) { location.href = url(kind, { search: query, 'city[]': city, ...coords }); return; }
    // The existing symptom-to-specialty search is scoped to the configured active city.
    if (city.toLowerCase() !== config.city.toLowerCase() || coords) { location.href = url(kind, { search: query, 'city[]': city, ...coords }); return; }
    abort.current?.abort();
    const controller = new AbortController(); abort.current = controller;
    setLoading(true); setSearchError('');
    try {
      const response = await fetch(url('api.search', { q: query }), { signal: controller.signal, headers: { Accept: 'application/json' } });
      if (!response.ok) throw new Error('Search unavailable');
      setResults(await response.json());
    } catch (error) { if (error.name !== 'AbortError') setSearchError(t('Search is unavailable. Please retry or browse the directory.', 'खोज उपलब्ध नहीं है। दोबारा प्रयास करें या निर्देशिका देखें।')); }
    finally { if (!controller.signal.aborted) setLoading(false); }
  }
  return <>
    <Paper component="form" onSubmit={submit} sx={{ p: { xs: 2, md: 3 }, boxShadow: '0 4px 20px rgba(23,43,58,.07)' }}>
      <Stack direction={{ xs: 'column', md: 'row' }} justifyContent="space-between" gap={2} sx={{ mb: 3 }}>
        <Tabs value={tab} onChange={(_, value) => setTab(value)} aria-label={t('Search mode', 'खोज का प्रकार')} variant="scrollable" scrollButtons="auto" sx={{ bgcolor: 'action.hover', borderRadius: 1, minHeight: 44, '& .MuiTabs-indicator': { display: 'none' }, '& .Mui-selected': { bgcolor: 'primary.main', color: 'primary.contrastText', borderRadius: 1 } }}><Tab icon={<LocalHospital fontSize="small" />} iconPosition="start" label={t('Find Healthcare Providers', 'स्वास्थ्य सेवा खोजें')} /><Tab icon={<Brain fontSize="small" />} iconPosition="start" label={t('Ask Health Question (Jeeva)', 'जीवा से स्वास्थ्य प्रश्न पूछें')} /></Tabs>
        <Stack direction="row" gap={1} alignItems="center"><CityPicker /><Button size="small" onClick={locate} disabled={gps} startIcon={gps ? <CircularProgress size={16} /> : <Near fontSize="small" />}>{t('Near Me', 'मेरे पास')}</Button></Stack>
      </Stack>
      <Stack direction={{ xs: 'column', md: 'row' }} gap={1.5}>
        <TextField id="main-search-input" fullWidth label={tab ? t('Your health question', 'आपका स्वास्थ्य प्रश्न') : t('Specialty, doctor, clinic, or hospital', 'विशेषज्ञता, डॉक्टर, क्लिनिक या अस्पताल')} value={query} onChange={e => setQuery(e.target.value)} slotProps={{ input: { startAdornment: <InputAdornment position="start"><Search /></InputAdornment>, endAdornment: query ? <IconButton aria-label={t('Clear search', 'खोज मिटाएँ')} onClick={() => { setQuery(''); setResults(null); }}><Close fontSize="small" /></IconButton> : null } }} />
        {!tab && <TextField select label={t('Search in', 'इसमें खोजें')} value={kind} onChange={e => setKind(e.target.value)} sx={{ minWidth: { md: 170 } }}>{services.filter(s => ['doctors.index', 'hospitals.index', 'blood_banks.index', 'medicines.index', 'nani-dadi.index'].includes(s[0])).map(s => <MenuItem key={s[0]} value={s[0]}>{s[2]}</MenuItem>)}</TextField>}
        <Button type="submit" variant="contained" disabled={loading} startIcon={loading ? <CircularProgress size={18} color="inherit" /> : tab ? <Robot /> : <Search />} sx={{ minWidth: { md: 190 }, minHeight: 54 }}>{tab ? t('Ask Jeeva', 'जीवा से पूछें') : t('Search Directory', 'निर्देशिका खोजें')}</Button>
      </Stack>
      <Stack direction="row" gap={1} flexWrap="wrap" alignItems="center" sx={{ mt: 2 }}><Typography variant="caption" color="text.secondary" sx={{ fontWeight: 700 }}>{t('QUICK INQUIRIES:', 'त्वरित खोज:')}</Typography>{[[t('Cardiologist near me', 'हृदय रोग विशेषज्ञ'), 'Cardiology', 'doctors.index'], [t('ESIC Hospitals', 'ESIC अस्पताल'), '', 'hospitals.index'], [t('O-Negative Blood', 'O-नेगेटिव रक्त'), '', 'blood_banks.index'], [t('Paracetamol uses', 'पैरासिटामोल के उपयोग'), 'Paracetamol', 'medicines.index'], [t('Remedy for cough', 'खाँसी का नुस्खा'), 'cough', 'nani-dadi.index']].map(([label, value, route]) => <Chip key={label} label={label} size="small" variant="outlined" onClick={() => { if (route === 'hospitals.index') location.href = url(route, { 'benefit[]': 'esic', 'city[]': city }); else if (route === 'blood_banks.index') location.href = url(route, { 'blood_group[]': 'O-', 'city[]': city }); else { setTab(0); setKind(route); setQuery(value); document.getElementById('main-search-input')?.focus(); } }} />)}</Stack>
      {notice && <Alert severity="info" onClose={() => setNotice('')} sx={{ mt: 2 }}>{notice}</Alert>}
    </Paper>
    {searchError && <Alert severity="error" sx={{ mt: 2 }} action={<Button href={url('doctors.index', { search: query, 'city[]': city })}>{t('Browse', 'देखें')}</Button>}>{searchError}</Alert>}
    {results && <Paper sx={{ p: 3, mt: 2 }} aria-live="polite"><Stack direction="row" justifyContent="space-between"><Typography variant="h3">{t('Search results', 'खोज परिणाम')} · {city}</Typography><IconButton aria-label={t('Close results', 'परिणाम बंद करें')} onClick={() => setResults(null)}><Close /></IconButton></Stack>{results.doctors?.length ? <Box className="design-grid three" sx={{ mt: 2 }}>{results.doctors.map(d => <Card key={d.id} sx={{ p: 2 }}><Typography variant="h3">{[d.first_name, d.last_name].filter(Boolean).join(' ')}</Typography><Typography color="primary">{d.department?.name?.[config.locale] || d.department?.name?.en}</Typography><Typography variant="body2" color="text.secondary" sx={{ my: 1 }}>{d.hospitals?.[0]?.name?.[config.locale] || d.hospitals?.[0]?.name?.en}</Typography>{d.phone_1 && <Button href={`tel:${String(d.phone_1).replace(/[^+\d]/g, '')}`}>{t('Call provider', 'प्रदाता को कॉल करें')}</Button>}<Button href={url('doctors.index', { search: [d.first_name, d.last_name].filter(Boolean).join(' '), 'city[]': city })} endIcon={<Arrow />}>{t('View listing', 'विवरण देखें')}</Button></Card>)}</Box> : <Alert severity="info" sx={{ mt: 2 }}>{t('No matching records found. Try a specialty or browse the directory.', 'कोई रिकॉर्ड नहीं मिला। विशेषज्ञता खोजें या निर्देशिका देखें।')}</Alert>}<Button href={url('doctors.index', { search: query, 'city[]': city })} sx={{ mt: 2 }}>{t('Open full directory', 'पूरी निर्देशिका खोलें')}</Button></Paper>}
  </>;
}
function RequestForm({ feedback = false }) {
  const initial = data.old || {};
  const [rating, setRating] = useState(Number(initial.rating) || 0);
  const [errors, setErrors] = useState(data.errors || {});
  const [busy, setBusy] = useState(false);
  const [done, setDone] = useState('');
  async function submit(e) {
    e.preventDefault(); setBusy(true); setErrors({}); setDone('');
    const form = e.currentTarget;
    try {
      const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json', 'X-CSRF-TOKEN': config.csrf } });
      if (response.status === 422) { const payload = await response.json(); setErrors(payload.errors || { form: [payload.message] }); return; }
      if (!response.ok) throw new Error('Submit failed');
      setDone(feedback ? t('Thank you. Your feedback helps improve Arogio.', 'धन्यवाद। आपकी प्रतिक्रिया Arogio को बेहतर बनाती है।') : t('Your consultation request has been received. This is a request, not a confirmed appointment.', 'आपका परामर्श अनुरोध मिल गया है। यह अनुरोध है, पुष्टि की गई अपॉइंटमेंट नहीं।'));
      form.reset(); setRating(0);
    } catch { setErrors({ form: [t('Unable to submit. Please try again.', 'जमा नहीं हो सका। कृपया दोबारा प्रयास करें।')] }); }
    finally { setBusy(false); }
  }
  const field = (name, label, type = 'text', extra = {}) => <TextField key={name} name={name} label={label} type={type} required fullWidth defaultValue={initial[name] || ''} error={Boolean(errors[name])} helperText={errors[name]?.[0]} slotProps={{ inputLabel: type === 'date' || type === 'time' ? { shrink: true } : undefined, htmlInput: name === 'preferred_date' ? { min: config.today } : name === 'reason' ? { minLength: 10, maxLength: 2000 } : { maxLength: name === 'comments' ? 2000 : 255 } }} {...extra} />;
  return <Box component="form" action={url(feedback ? 'feedback.submit' : 'consultation-requests.submit')} onSubmit={submit} method="post" sx={{ mt: 3 }}><input type="hidden" name="_token" value={config.csrf} />
    {done && <Alert severity="success" sx={{ mb: 2 }}>{done}</Alert>}{errors.form && <Alert severity="error" sx={{ mb: 2 }}>{errors.form[0]}</Alert>}
    <Box className="design-grid two" sx={{ gap: 2 }}>{field('name', t('Full name', 'पूरा नाम'))}{!feedback && field('email', t('Email address', 'ईमेल'), 'email')}{!feedback && field('phone', t('Phone number', 'फ़ोन नंबर'), 'tel')}{!feedback && field('preferred_date', t('Preferred date', 'पसंदीदा तारीख'), 'date')}{!feedback && field('preferred_time', t('Preferred time', 'पसंदीदा समय'), 'time')}{feedback && field('category', t('Feedback category', 'प्रतिक्रिया श्रेणी'), 'text', { select: true, defaultValue: initial.category || 'General', children: ['General', 'Website Experience', 'Data Accuracy', 'Feature Request'].map(v => <MenuItem key={v} value={v}>{v}</MenuItem>) })}</Box>
    {feedback && <Box sx={{ my: 2 }}><Typography component="label" id="feedback-rating-label">{t('Your experience', 'आपका अनुभव')}</Typography><Box><Rating name="rating" value={rating} onChange={(_, v) => setRating(v || 0)} aria-labelledby="feedback-rating-label" getLabelText={v => t(`${v} Stars`, `${v} सितारे`)} /></Box>{errors.rating && <Typography color="error" variant="caption">{errors.rating[0]}</Typography>}</Box>}
    <Box sx={{ mt: 2 }}>{field(feedback ? 'comments' : 'reason', feedback ? t('Your feedback', 'आपकी प्रतिक्रिया') : t('Reason for consultation', 'परामर्श का कारण'), 'text', { multiline: true, minRows: 3 })}</Box>
    <Button type="submit" variant="contained" disabled={busy} startIcon={busy ? <CircularProgress size={18} color="inherit" /> : feedback ? <Check /> : <Calendar />} sx={{ mt: 2 }}>{feedback ? t('Send feedback', 'प्रतिक्रिया भेजें') : t('Submit consultation request', 'परामर्श अनुरोध भेजें')}</Button>
  </Box>;
}
function Home() {
  const [city] = useCity();
  const article = data.articles?.[0];
  const remedy = data.remedy;
  const departmentIcons = [Doctor, Heart, Bone, Baby, Woman, Leaf, Brain, Eye];
  return <Box component="main" id="main-content" tabIndex={-1} sx={{ bgcolor: 'background.default', color: 'text.primary' }}>
    <Box className="design-hero" sx={{ pt: { xs: 4, md: 5 }, pb: 5 }}><Container>
      <Chip icon={<Check />} label={t('100% Free, Ad-Free Public Healthcare Directory', 'निःशुल्क और विज्ञापन-मुक्त स्वास्थ्य निर्देशिका')} size="small" sx={{ mb: 2, color: 'primary.main', bgcolor: 'action.hover', fontWeight: 700, maxWidth: '100%', '& .MuiChip-label': { whiteSpace: 'normal' }, height: 'auto', py: .4 }} />
      <Typography variant="h1" sx={{ fontSize: { xs: 32, md: 42 }, mb: 1.5 }}>{t('Find the right care,', 'सही स्वास्थ्य सेवा खोजें,')} <Box component="span" sx={{ color: 'primary.main', textDecoration: 'underline', textDecorationColor: 'rgba(0,121,107,.25)', textUnderlineOffset: 8 }}>{t('closer to you.', 'अपने आस-पास।')}</Box></Typography>
      <Typography sx={{ fontSize: 18, color: 'text.secondary', maxWidth: 800, mb: 4 }}>{t('Discover doctors, hospitals, blood banks, and reliable everyday health information across India — completely free and ad-free.', 'भारत में डॉक्टर, अस्पताल, ब्लड बैंक और रोज़मर्रा की स्वास्थ्य जानकारी खोजें — पूरी तरह निःशुल्क और विज्ञापन-मुक्त।')}</Typography>
      <SearchPanel />
    </Container></Box>
    <Box sx={{ bgcolor: 'rgba(211,47,47,.075)', py: 1.5, borderBlock: '1px solid rgba(211,47,47,.1)' }}><Container><Stack direction={{ xs: 'column', sm: 'row' }} justifyContent="space-between" gap={2} alignItems={{ xs: 'flex-start', sm: 'center' }}><Stack direction="row" gap={1.5} alignItems="center"><IconTile icon={Health} color="#c62828" tint="#ffdad6" /><Box><Typography fontWeight={750} color="error">{t('Need emergency help immediately?', 'तुरंत आपातकालीन सहायता चाहिए?')}</Typography><Typography variant="body2">{t('Find emergency resources. Arogio does not provide emergency response.', 'आपातकालीन संसाधन देखें। Arogio आपातकालीन प्रतिक्रिया सेवा नहीं है।')}</Typography></Box></Stack><Button href={url('emergency')} color="error" variant="outlined" endIcon={<Arrow />} sx={{ flexShrink: 0 }}>{t('Emergency resources', 'आपातकालीन संसाधन')}</Button></Stack></Container></Box>
    <Container sx={{ py: 5 }}>
      <section><SectionHeading title={t('Essential Healthcare Services', 'ज़रूरी स्वास्थ्य सेवाएँ')} subtitle={t('Everything you need to make informed healthcare decisions.', 'स्वास्थ्य से जुड़े बेहतर निर्णय लेने के लिए उपयोगी जानकारी।')} /><Box className="design-grid three">{services.map(([route, Icon, title, desc, cta, color, tint]) => <Card key={route}><CardActionArea href={route === 'symptom' ? data.symptomUrl : url(route, ['doctors.index', 'hospitals.index', 'blood_banks.index'].includes(route) ? { 'city[]': city } : {})} sx={{ p: 3, height: '100%', display: 'flex', flexDirection: 'column', alignItems: 'flex-start' }}><IconTile icon={Icon} color={color} tint={tint} /><Typography variant="h3" sx={{ mt: 2, mb: 1 }}>{title}</Typography><Typography variant="body2" color="text.secondary" sx={{ flexGrow: 1, mb: 2 }}>{desc}</Typography><Stack direction="row" justifyContent="space-between" sx={{ width: '100%', pt: 1.5, borderTop: 1, borderColor: 'divider', color: 'primary.main' }}><Typography variant="body2" fontWeight={750}>{cta}</Typography><Arrow fontSize="small" /></Stack></CardActionArea></Card>)}</Box></section>
      <Paper component="section" className="design-jeeva" sx={{ my: 5, p: { xs: 3, md: 4 }, border: 1, borderColor: 'divider' }}><Box className="design-grid two" sx={{ alignItems: 'center', gap: 4 }}><Box><Stack direction="row" gap={1} alignItems="center" sx={{ mb: 1.5 }}><Robot color="primary" /><Typography variant="overline" color="primary" fontWeight={750}>{t('YOUR HEALTH DISCOVERY ASSISTANT', 'आपका स्वास्थ्य जानकारी सहायक')}</Typography></Stack><Typography variant="h2">{t('Meet Jeeva — Your Health Discovery Guide', 'मिलिए जीवा से — आपका स्वास्थ्य मार्गदर्शक')}</Typography><Typography color="text.secondary" sx={{ my: 2 }}>{t('Not sure where to start? Ask in your own words. Jeeva helps you explore health information and find the right type of care.', 'कहाँ से शुरू करें? अपने शब्दों में पूछें। जीवा स्वास्थ्य जानकारी और सही सेवा खोजने में मदद करता है।')}</Typography><Alert severity="info" icon={<Health />} sx={{ mb: 2 }}>{t('Jeeva provides information, not a medical diagnosis. For personal medical advice, consult a qualified professional.', 'जीवा जानकारी देता है, चिकित्सीय निदान नहीं। व्यक्तिगत सलाह के लिए योग्य चिकित्सक से संपर्क करें।')}</Alert><Button variant="contained" startIcon={<Robot />} onClick={() => askJeeva()}>{t('Start a conversation', 'बातचीत शुरू करें')}</Button></Box><Paper variant="outlined" sx={{ p: 3 }}><Stack direction="row" spacing={1} alignItems="center"><IconTile icon={Robot} /><Box><Typography fontWeight={750}>Jeeva</Typography><Typography variant="caption" color="text.secondary">{t('Healthcare discovery assistant', 'स्वास्थ्य जानकारी सहायक')}</Typography></Box></Stack><Box sx={{ bgcolor: 'action.hover', p: 2, borderRadius: '0 12px 12px 12px', my: 2 }}><Typography>{t('Hello! How can I help you explore healthcare today?', 'नमस्ते! आज स्वास्थ्य जानकारी खोजने में मैं आपकी कैसे मदद करूँ?')}</Typography></Box><Stack gap={1}>{[t('Which specialist should I see for joint pain?', 'जोड़ों के दर्द के लिए किस विशेषज्ञ से मिलूँ?'), t('How can I find hospitals in my city?', 'अपने शहर में अस्पताल कैसे खोजूँ?'), t('What should I know about medicine side effects?', 'दवाओं के दुष्प्रभावों के बारे में क्या जानना चाहिए?')].map(q => <Button key={q} variant="outlined" onClick={() => askJeeva(q)} endIcon={<Arrow />} sx={{ justifyContent: 'space-between', textAlign: 'left' }}>{q}</Button>)}</Stack><Typography variant="caption" color="text.secondary" sx={{ display: 'block', mt: 2 }}>{t('Choose a question to continue in Jeeva.', 'जीवा में आगे बढ़ने के लिए प्रश्न चुनें।')}</Typography></Paper></Box></Paper>
      <section><SectionHeading title={t('Browse by Medical Department', 'चिकित्सा विभाग के अनुसार खोजें')} subtitle={t('Find the right specialist for your healthcare needs.', 'अपनी ज़रूरत के अनुसार सही विशेषज्ञ खोजें।')} link={url('departments.index')} linkText={t('All departments', 'सभी विभाग')} /><Box className="design-grid four">{(data.departments || []).slice(0, 8).map((d, i) => { const Icon = departmentIcons[i % departmentIcons.length]; return <Card key={d.id}><CardActionArea href={url('doctors.index', { 'department[]': d.id, 'city[]': city })} sx={{ p: 2.5, textAlign: 'center' }}><Icon sx={{ color: 'primary.main', fontSize: 30, mb: 1 }} /><Typography fontWeight={700}>{d.name[config.locale] || d.name.en}</Typography></CardActionArea></Card>; })}</Box>{!data.departments?.length && <Alert severity="info">{t('Department listings are being updated. Browse the doctor directory for available records.', 'विभाग की जानकारी अपडेट हो रही है। उपलब्ध रिकॉर्ड के लिए डॉक्टर निर्देशिका देखें।')}</Alert>}</section>
      <Box component="section" sx={{ my: 5 }}><SectionHeading title={t('Health Knowledge & Traditional Remedies', 'स्वास्थ्य ज्ञान और पारंपरिक नुस्खे')} subtitle={t('Practical information, with evidence and safety in focus.', 'प्रमाण और सुरक्षा को ध्यान में रखते हुए उपयोगी जानकारी।')} link={url('articles.index')} linkText={t('Explore health articles', 'स्वास्थ्य लेख देखें')} /><Box className="design-grid two">
        <Card sx={{ display: 'flex', flexDirection: 'column' }}><Box className="design-remedy-art"><img src={config.images.remedies} alt="" loading="lazy" width="600" height="200" style={{ width: '100%', height: '100%', objectFit: 'cover' }} /><Chip label={t('Nani Dadi Ke Nuskhe', 'नानी दादी के नुस्खे')} sx={{ bgcolor: '#fff', color: '#005e53', position: 'absolute', left: 20, bottom: 16 }} /></Box><Box sx={{ p: 3, flexGrow: 1 }}><Chip label={remedy?.evidence || t('Evidence & safety guidance', 'प्रमाण और सुरक्षा जानकारी')} size="small" variant="outlined" sx={{ mb: 1.5 }} /><Typography variant="h3">{remedy?.title || t('Traditional wisdom, thoughtful guidance', 'पारंपरिक ज्ञान, सोच-समझकर मार्गदर्शन')}</Typography><Typography color="text.secondary" variant="body2" sx={{ my: 1.5 }}>{remedy?.description || t('Explore home remedies by ingredient and concern, with clear explanations of evidence, precautions, and when to seek care.', 'सामग्री और समस्या के अनुसार नुस्खे देखें, प्रमाण, सावधानियाँ और डॉक्टर से मिलने की जानकारी के साथ।')}</Typography><Alert severity="warning" icon={<Warning />} sx={{ mb: 2 }}>{t('Natural does not always mean safe. Read precautions and medicine interactions before use.', 'प्राकृतिक होने का अर्थ हमेशा सुरक्षित नहीं है। उपयोग से पहले सावधानियाँ और दवा अंतःक्रियाएँ पढ़ें।')}</Alert><Button href={remedy?.url || url('nani-dadi.index')} endIcon={<Arrow />}>{t('Explore remedy details', 'नुस्खे की जानकारी देखें')}</Button></Box></Card>
        <Card sx={{ display: 'flex', flexDirection: 'column' }}><Box className="design-article-art"><img src={config.images.health} alt="" loading="lazy" width="600" height="200" style={{ width: '100%', height: '100%', objectFit: 'cover' }} /><Chip label={t('Health Information', 'स्वास्थ्य जानकारी')} sx={{ bgcolor: '#fff', color: '#1565c0', position: 'absolute', left: 20, bottom: 16 }} /></Box><Box sx={{ p: 3, flexGrow: 1 }}><Typography variant="overline" color="secondary">{article?.category || t('EVERYDAY HEALTH', 'रोज़मर्रा का स्वास्थ्य')}</Typography><Typography variant="h3">{article?.title || t('Understand your health, one article at a time', 'एक-एक लेख से अपने स्वास्थ्य को समझें')}</Typography><Typography variant="body2" color="text.secondary" sx={{ my: 1.5 }}>{article?.excerpt || t('Discover practical health information and lifestyle guidance in English and Hindi.', 'अंग्रेज़ी और हिंदी में उपयोगी स्वास्थ्य जानकारी और जीवनशैली मार्गदर्शन देखें।')}</Typography>{article?.author && <Typography variant="body2" sx={{ py: 1.5, borderTop: 1, borderColor: 'divider' }}>{t('By', 'लेखक')}: {article.author}</Typography>}<Button href={article?.url || url('articles.index')} endIcon={<Arrow />}>{t('Read health article', 'स्वास्थ्य लेख पढ़ें')}</Button></Box></Card>
      </Box></Box>
      <Box component="section" sx={{ mb: 5 }}><SectionHeading title={t('A little time for your wellbeing', 'अपनी भलाई के लिए थोड़ा समय')} subtitle={t('Simple activities to pause, learn, and recharge.', 'रुकने, सीखने और तरोताज़ा होने की सरल गतिविधियाँ।')} link={url('activities.index')} /><Box className="design-grid four">{[['activities.breathing', Leaf, t('Breathing & calm', 'श्वास और शांति')], ['activities.grounding', Health, t('Grounding', 'ग्राउंडिंग')], ['activities.mood-check', Heart, t('Mood check-in', 'मूड चेक-इन')], ['activities.calm-audio', Headphones, t('Calming audio', 'शांतिदायक ऑडियो')], ['activities.games.memory', Games, t('Memory game', 'स्मृति खेल')], ['activities.games.calm-tap', Games, t('Calm tap', 'शांत टैप')], ['quizzes.index', Brain, t('Health quizzes', 'स्वास्थ्य प्रश्नोत्तरी')], ['medicines.index', Medicine, t('Medicine information', 'दवा की जानकारी')]].map(([route, Icon, title]) => <Card key={route}><CardActionArea href={url(route)} sx={{ display: 'flex', gap: 1.5, alignItems: 'center', p: 2 }}><Icon color="primary" /><Typography fontWeight={700}>{title}</Typography></CardActionArea></Card>)}</Box></Box>
      <Box component="section" id="schedule-consultation" className="design-grid consultation" sx={{ scrollMarginTop: 100 }}><Paper variant="outlined" sx={{ p: { xs: 2.5, md: 4 } }}><IconTile icon={Calendar} /><Typography variant="h2" sx={{ mt: 2 }}>{t('Need to consult a specialist?', 'विशेषज्ञ से परामर्श चाहिए?')}</Typography><Typography color="text.secondary" sx={{ mt: 1 }}>{t('Share your preferred time and concern. A request does not confirm an appointment.', 'अपना पसंदीदा समय और समस्या बताएँ। अनुरोध से अपॉइंटमेंट की पुष्टि नहीं होती।')}</Typography><RequestForm /><Divider sx={{ my: 2 }} /><Button href={url('consultations.index')} startIcon={<Video />}>{t('Enter a video consultation room', 'वीडियो परामर्श कक्ष में जाएँ')}</Button></Paper><Paper variant="outlined" sx={{ p: { xs: 3, md: 4 }, bgcolor: 'action.hover' }}><IconTile icon={People} /><Typography variant="h2" sx={{ mt: 2 }}>{t('Suggest a Missing Clinic or Provider', 'छूटे हुए क्लिनिक या प्रदाता को सुझाएँ')}</Typography><Typography color="text.secondary" sx={{ my: 2 }}>{t('Know a doctor or hospital that should be listed? Help your community discover more healthcare options.', 'क्या कोई डॉक्टर या अस्पताल सूची में नहीं है? अपने समुदाय को अधिक स्वास्थ्य विकल्प खोजने में मदद करें।')}</Typography><Stack gap={2} sx={{ my: 3 }}>{[t('Share the provider’s details', 'प्रदाता की जानकारी साझा करें'), t('Our team reviews the submission', 'हमारी टीम जानकारी की समीक्षा करती है'), t('Approved records join the directory', 'स्वीकृत रिकॉर्ड निर्देशिका में जुड़ते हैं')].map((label, i) => <Stack direction="row" gap={1.5} alignItems="center" key={label}><Chip label={i + 1} size="small" color="primary" /><Typography>{label}</Typography></Stack>)}</Stack><Button href={url('suggestions.create')} variant="outlined" endIcon={<Arrow />}>{t('Suggest a provider', 'प्रदाता सुझाएँ')}</Button></Paper></Box>
      {data.articles?.length > 1 && <Box component="section" sx={{ mt: 5 }}><SectionHeading title={t('More from Arogio', 'Arogio से और जानकारी')} link={url('articles.index')} /><Box className="design-grid three">{data.articles.slice(1, 4).map(a => <Card key={a.url}><CardActionArea href={a.url} sx={{ p: 3, height: '100%' }}><Typography variant="overline" color="primary">{a.category}</Typography><Typography variant="h3">{a.title}</Typography><Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>{a.excerpt}</Typography></CardActionArea></Card>)}</Box></Box>}
      {!!data.faqs?.length && <Box component="section" sx={{ mt: 5, maxWidth: 850, mx: 'auto' }}><SectionHeading title={t('Frequently Asked Questions', 'अक्सर पूछे जाने वाले प्रश्न')} />{data.faqs.map((f, i) => <Accordion key={i} sx={{ mb: 1 }}><AccordionSummary expandIcon={<Down />}><Typography fontWeight={700}>{f.question}</Typography></AccordionSummary><AccordionDetails><Typography color="text.secondary">{f.answer}</Typography></AccordionDetails></Accordion>)}</Box>}
      <Accordion sx={{ mt: 5 }}><AccordionSummary expandIcon={<Down />}><Typography variant="h3">{t('Help us make Arogio better — share your feedback', 'Arogio को बेहतर बनाने में मदद करें — अपनी प्रतिक्रिया दें')}</Typography></AccordionSummary><AccordionDetails><RequestForm feedback /></AccordionDetails></Accordion>
    </Container>
  </Box>;
}
function Footer() {
  return <Box component="footer" sx={{ bgcolor: 'background.paper', color: 'text.primary', borderTop: 1, borderColor: 'divider', pt: 5, pb: { xs: 11, md: 3 } }}><Container><Box className="design-grid footer"><Box><img className="design-footer-logo" src={config.logo} alt="Arogio" width="135" /><Typography color="text.secondary" variant="body2" sx={{ mt: 2, maxWidth: 310 }}>{t('A free, ad-free healthcare discovery platform for India. Find care and understand your health, in your language.', 'भारत के लिए निःशुल्क, विज्ञापन-मुक्त स्वास्थ्य खोज मंच। अपनी भाषा में सेवाएँ खोजें और स्वास्थ्य को समझें।')}</Typography></Box>{[...groups.slice(0, 2), [t('Arogio & Support', 'Arogio और सहायता'), [['about', t('About us', 'हमारे बारे में')], ['contact', t('Contact', 'संपर्क')], ['suggestions.create', t('Suggest a provider', 'प्रदाता सुझाएँ')], ['activities.index', t('Wellness activities', 'वेलनेस गतिविधियाँ')], ['support.crisis', t('Crisis support', 'संकट सहायता')]]]].map(([title, links]) => <Box key={title}><Typography fontWeight={750} sx={{ mb: 1.5 }}>{title}</Typography><Stack gap={1}>{links.map(([route, label]) => <Typography key={route} component="a" href={url(route)} variant="body2" color="text.secondary" sx={{ '&:hover': { color: 'primary.main' } }}>{label}</Typography>)}</Stack></Box>)}</Box><Alert severity="info" icon={<Health />} sx={{ my: 3 }}>{t('Arogio helps you discover healthcare providers and health information. We do not provide diagnosis, treatment, or emergency response. Please call before visiting.', 'Arogio स्वास्थ्य सेवा प्रदाता और जानकारी खोजने में मदद करता है। हम निदान, उपचार या आपातकालीन प्रतिक्रिया प्रदान नहीं करते। जाने से पहले कॉल करें।')}</Alert><Stack direction={{ xs: 'column', sm: 'row' }} justifyContent="space-between" gap={1} sx={{ borderTop: 1, borderColor: 'divider', pt: 2 }}><Typography variant="caption" color="text.secondary">© {config.year} Arogio. {t('Free. Accessible. Ad-free.', 'निःशुल्क। सुलभ। विज्ञापन-मुक्त।')}</Typography><Stack direction="row" gap={3}><a href={url('privacy.policy')}>{t('Privacy Policy', 'गोपनीयता नीति')}</a><a href={url('terms.service')}>{t('Terms of Service', 'सेवा की शर्तें')}</a></Stack></Stack></Container></Box>;
}
for (const [id, Component] of [['arogio-header', Header], ['arogio-home', Home], ['arogio-directory', Directory], ['arogio-symptoms', Symptoms], ['arogio-remedies', Remedies], ['arogio-jeeva', Jeeva], ['arogio-footer', Footer]]) {
  const root = document.getElementById(id);
  if (root) createRoot(root).render(<DesignTheme><Component /></DesignTheme>);
}

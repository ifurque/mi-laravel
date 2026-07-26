import zipfile, xml.etree.ElementTree as ET, json, os, re, sys

DOCX = r'resources\INFORME E.P.N°33.docx'
OUT_MD = r'resources\INFORME_pages_2-17.md'
OUT_JSON = r'resources\INFORME_pages_2-17.json'
OUT_IMG_DIR = r'public\images\docx_media'

ns = {'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}

if not os.path.exists(OUT_IMG_DIR):
    os.makedirs(OUT_IMG_DIR, exist_ok=True)

try:
    with zipfile.ZipFile(DOCX) as z:
        # extract document.xml
        doc_xml = z.read('word/document.xml').decode('utf-8')
        # extract media files
        media_files = [f for f in z.namelist() if f.startswith('word/media/')]
        img_map = []
        for i, mf in enumerate(media_files, start=1):
            target = os.path.join(OUT_IMG_DIR, os.path.basename(mf))
            with open(target, 'wb') as out:
                out.write(z.read(mf))
            img_map.append({'name': os.path.basename(mf), 'path': target})

    root = ET.fromstring(doc_xml)
    body = root.find('w:body', ns)
    pages = [[]]

    for block in list(body):
        tag = block.tag
        if tag.endswith('}p'):
            texts = [t.text for t in block.findall('.//w:t', ns) if t.text]
            text = ''.join(texts).strip()
            pstyle = block.find('.//w:pStyle', ns)
            style = pstyle.get('{http://schemas.openxmlformats.org/wordprocessingml/2006/main}val') if pstyle is not None else ''
            has_page_break = False
            for br in block.findall('.//w:br', ns):
                tp = br.get('{http://schemas.openxmlformats.org/wordprocessingml/2006/main}type')
                if tp == 'page':
                    has_page_break = True
            if block.find('.//w:lastRenderedPageBreak', ns) is not None:
                has_page_break = True

            pages[-1].append({'type':'p','text':text,'style':style})
            if has_page_break:
                pages.append([])
        elif tag.endswith('}tbl'):
            rows = []
            for tr in block.findall('.//w:tr', ns):
                cells = []
                for tc in tr.findall('.//w:tc', ns):
                    cell_texts = [t.text for t in tc.findall('.//w:t', ns) if t.text]
                    cells.append(' '.join(cell_texts).strip())
                rows.append(cells)
            pages[-1].append({'type':'table','rows':rows})
        else:
            pass

    start=2; end=17
    collected = []
    for pnum in range(start, min(end, len(pages))+1):
        page_blocks = pages[pnum-1]
        sections = []
        current = {'title': None, 'blocks': []}
        for b in page_blocks:
            if b['type']=='p':
                txt = b['text']
                if not txt:
                    continue
                if re.search(r'encuesta|formulario|https?://', txt, re.I):
                    continue
                if re.search(r'\bclase\b|en clase|actividad(es)? de clase|se trabaj', txt, re.I):
                    continue
                style = b.get('style','')
                is_heading = False
                if style and str(style).lower().startswith('heading'):
                    is_heading = True
                if txt.isupper() and len(txt)>3:
                    is_heading = True
                if is_heading:
                    if current['title'] or current['blocks']:
                        sections.append(current)
                    current = {'title': txt.strip(), 'blocks': []}
                else:
                    current['blocks'].append({'type':'p','text':txt})
            elif b['type']=='table':
                current['blocks'].append({'type':'table','rows':b['rows']})
        if current['title'] or current['blocks']:
            sections.append(current)
        collected.append({'page': pnum, 'sections': sections})

    with open(OUT_JSON,'w',encoding='utf8') as jf:
        json.dump({'pages':collected,'images':img_map}, jf, ensure_ascii=False, indent=2)

    with open(OUT_MD,'w',encoding='utf8') as mf:
        mf.write('# Extracted pages 2-17\n\n')
        for page in collected:
            mf.write('## Page %d\n\n' % page['page'])
            for sec in page['sections']:
                title = sec['title'] or 'Sin título'
                mf.write('### %s\n\n' % title)
                for blk in sec['blocks']:
                    if blk['type']=='p':
                        mf.write(blk['text'] + '\n\n')
                    elif blk['type']=='table':
                        for r_idx, row in enumerate(blk['rows']):
                            mf.write('| ' + ' | '.join(cell or '' for cell in row) + ' |\n')
                            if r_idx==0:
                                mf.write('|' + '---|'*len(row) + '\n')
                        mf.write('\n')

    print('WROTE', OUT_MD, OUT_JSON)
except Exception as e:
    print('ERROR', e)
    raise

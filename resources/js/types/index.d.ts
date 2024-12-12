import { Config } from 'ziggy-js';

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User;
  };
  ziggy: Config & { location: string };
};

export interface PostRating {
  id: number;
  post_id: number;
  user_id: number;
  rating: number;
  created_at: string;
}

export interface Post {
  id: number;
  title: string;
  content: string;
  slug: string;
  user_id: number;
  media?: [];
  user: User;
  comments_count?: number;
  created_at: string;
  ratings?: PostRating[];
  ratings_count?: number;
  average_rating?: number;
}

export interface Comment {
  id: number;
  content: string;
  user_id: number;
  post_id: number;
  user: User;
  created_at: string;
}

export interface PostRating {
  id: number;
  user_id: number;
  post_id: number;
  rating: number;
}

export interface Role {
  id: number;
  name: string;
}

// export interface PaginatedResponse<T> {
//   data: T[];
//   current_page: number;
//   last_page: number;
//   total: number;
// }

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  total: number;
  links: PaginationLink[];
  from: number;
  to: number;
  per_page: number;
}
